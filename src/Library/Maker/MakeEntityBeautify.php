<?php

namespace App\Library\Maker;

use App\Library\Maker\Utils\FileManager;
use App\Library\Maker\Utils\Manipulator;
use App\Library\Maker\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\NamingStrategy;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Comment\Doc;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassDetails;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class MakeEntityBeautify extends AbstractMaker
{
    private $projectDir;
    private $managerRegistry;
    private $fs;
    private $fileManager;

    public function __construct(string $projectDir, ManagerRegistry $managerRegistry, Filesystem $fs)
    {
        $this->projectDir = $projectDir;
        $this->managerRegistry = $managerRegistry;
        $this->fs = $fs;
        $this->fileManager = $this->createFileManager($projectDir, $fs);
    }

    public static function getCommandName(): string
    {
        return 'make:entity:beautify';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->setDescription('Update entities with some own custom modifications')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                sprintf(
                    'Class name of the entity to update (e.g. <fg=yellow>%s</>)',
                    Str::asClassName(Str::getRandomTerm())
                )
            )
        ;

        $inputConfig->setArgumentAsNonInteractive('name');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if ($input->getArgument('name')) {
            return;
        }

        $argument = $command->getDefinition()->getArgument('name');
        $question = $this->getEntityClassQuestion($argument->getDescription());
        $value = $io->askQuestion($question);

        $input->setArgument('name', $value);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $entityName = $input->getArgument('name');
        $entityClassDetails = $generator->createClassNameDetails($entityName, 'Entity\\');

        $classExists = class_exists($entityClassDetails->getFullName());
        if (!$classExists) {
            throw new RuntimeCommandException('Entity does not exist at the moment. Create it first.');
        }

        $entityPath = $this->getPathOfClass($entityClassDetails->getFullName());
        $io->text('Entity found! Let\'s do the modifications...');

        $manipulator = $this->createClassManipulator($entityPath, $io);

        $this->doTableUpdates($io, $manipulator, $entityName, $entityPath);
        $this->doFieldsUpdates($io, $manipulator, $entityName, $entityPath);
    }

    private function doTableUpdates(
        ConsoleStyle $io,
        Manipulator $manipulator,
        string $entityName,
        string $entityPath
    ): void {
        $io->text('1. Table name');

        $tableName = $this->getTableName($entityName);

        $docComment = $manipulator->getClassNode()->getDocComment();
        $docLines = $docComment ? explode("\n", $docComment->getText()) : [];

        $tableFound = false;
        foreach ($docLines as $key => $docLine) {
            if (strpos($docLine, '@ORM\Table') !== false) {
                $tableFound = true;
                $lineData = $manipulator->getDataFromOrmAnnotation($docLine);
                $lineData['name'] = $tableName;

                $docLines[$key] = $manipulator->convertDataToOrmAnnotation(Manipulator::TABLE_ORM_TYPE, $lineData);
            }
        }

        if ($tableFound === false) {
            array_splice(
                $docLines,
                \count($docLines) - 1,
                0,
                $manipulator->convertDataToOrmAnnotation(Manipulator::TABLE_ORM_TYPE, ['name' => $tableName])
            );
        }

        $docComment = new Doc(implode("\n", $docLines));

        $manipulator->getClassNode()->setDocComment($docComment);
        $manipulator->updateSourceCodeFromNewStmts();
        $this->fileManager->dumpFile($entityPath, $manipulator->getSourceCode());

        $io->success('Table name updated!');
    }

    private function doFieldsUpdates(
        ConsoleStyle $io,
        Manipulator $manipulator,
        string $entityName,
        string $entityPath
    ): void {
        $io->text('2. Fields updates');

        $properties = $manipulator->findPropertiesNodes();
        foreach ($properties as $property) {
            $propertyName = $property->props[0]->name;

            $docComment = $property->getDocComment();
            $docLines = $docComment ? explode("\n", $docComment->getText()) : [];

            $typeHintFound = false;
            $doctrineType = null;
            foreach ($docLines as $key => $docLine) {
                if (strpos($docLine, '@var') !== false) {
                    $typeHintFound = true;
                }

                if (strpos($docLine, '@ORM\Column') !== false) {
                    $lineData = $manipulator->getDataFromOrmAnnotation($docLine);

                    $doctrineType = $lineData['type'];
                    $fieldName = $this->getFieldName($propertyName, $entityName);
                    if (isset($lineData['name'])) {
                        unset($lineData['name']);
                    }

                    $lineData = ['name' => $fieldName] + $lineData;

                    $docLines[$key] = $manipulator->convertDataToOrmAnnotation(
                        Manipulator::COLUMN_ORM_TYPE,
                        $lineData
                    );
                }
            }

            if ($typeHintFound === false) {
                array_splice(
                    $docLines,
                    1,
                    0,
                    sprintf(' * @var %s|null', $manipulator->getEntityTypeHint($doctrineType))
                );
            }
            $docComment = new Doc(implode("\n", $docLines));
            $property->setDocComment($docComment);
        }

        $manipulator->updateSourceCodeFromNewStmts();
        $this->fileManager->dumpFile($entityPath, $manipulator->getSourceCode());

        $io->success('Fields updated!');
    }

    private function getTableName(string $entityName): string
    {
        return $this->getNamingStrategy()->classToTableName($entityName);
    }

    private function getFieldName(string $propertyName, string $entityName): string
    {
        return $this->getNamingStrategy()->propertyToColumnName($propertyName, $entityName);
    }

    private function getNamingStrategy(): NamingStrategy
    {
        $entityManager = $this->managerRegistry->getManager();
        if (!$entityManager instanceof EntityManagerInterface) {
            throw new \RuntimeException('ObjectManager is not an EntityManagerInterface.');
        }

        return $entityManager->getConfiguration()->getNamingStrategy();
    }

    private function getEntityClassQuestion(string $questionText): Question
    {
        $question = new Question($questionText);
        $question->setValidator([Validator::class, 'notBlank']);

        return $question;
    }

    private function getPathOfClass(string $class): string
    {
        return (new ClassDetails($class))->getPath();
    }

    private function getProperties(string $class): array
    {
        if (!class_exists($class)) {
            return [];
        }

        $reflectedClass = new \ReflectionClass($class);

        return array_map(function (\ReflectionProperty $prop) {
            return $prop;
        }, $reflectedClass->getProperties());
    }

    private function createClassManipulator(string $path, ConsoleStyle $io): Manipulator
    {
        return new Manipulator($io, $this->fileManager->getFileContents($path));
    }

    private function createFileManager(string $projectDir, Filesystem $fs): FileManager
    {
        return new FileManager($projectDir, $fs);
    }
}
