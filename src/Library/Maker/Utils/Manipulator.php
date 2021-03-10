<?php

namespace App\Library\Maker\Utils;

use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;
use PhpParser\Parser;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Util\PrettyPrinter;

/**
 * @SuppressWarnings(PHPMD)
 */
class Manipulator
{
    const TABLE_ORM_TYPE = 'Table';
    const COLUMN_ORM_TYPE = 'Column';

    private $parser;
    private $lexer;
    private $io;
    private $printer;

    /** @var string */
    private $sourceCode;

    /** @var Node\Stmt[] */
    private $oldStmts;

    /** @var array */
    private $oldTokens;

    /** @var Node[] */
    private $newStmts;

    public function __construct(ConsoleStyle $io, string $sourceCode)
    {
        $this->io = $io;
        $this->lexer = new Lexer\Emulative([
            'usedAttributes' => [
                'comments',
                'startLine', 'endLine',
                'startTokenPos', 'endTokenPos',
            ],
        ]);
        $this->parser = new Parser\Php7($this->lexer);
        $this->printer = new PrettyPrinter();

        $this->setSourceCode($sourceCode);
    }

    public function getSourceCode(): string
    {
        return $this->sourceCode;
    }

    /**
     * @return Node\Stmt\Class_
     * @throws \Exception
     */
    public function getClassNode(): Node\Stmt\Class_
    {
        $node = $this->findFirstNode(function ($node) {
            return $node instanceof Node\Stmt\Class_;
        });

        if (!$node instanceof Node\Stmt\Class_) {
            throw new \Exception('Could not find class node');
        }

        return $node;
    }

    public function getDataFromOrmAnnotation(string $annotation): array
    {
        $matches = null;
        preg_match_all('/\([^\]]*\)/', $annotation, $matches);

        $result = $matches[0][0];
        $result = str_replace(['(', ')', ',', '"'], '', $result);
        $result = str_replace(' ', '&', $result);

        parse_str($result, $data);

        return $data;
    }

    public function convertDataToOrmAnnotation(string $type, array $data): string
    {
        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = $this->getKeyValuePairStr($key, $value);
        }

        return sprintf(' * @ORM\%s(%s)', $type, implode(', ', $pairs));
    }

    /**
     * @return Node\Stmt\Property[]
     */
    public function findPropertiesNodes(): array
    {
        /** @var Node\Stmt\Property[] $propertiesNodes */
        $propertiesNodes = $this->findAllNodes(function ($node) {
            return $node instanceof Node\Stmt\Property;
        });

        return $propertiesNodes;
    }

    public function updateSourceCodeFromNewStmts(): void
    {
        $newCode = $this->printer->printFormatPreserving(
            $this->newStmts,
            $this->oldStmts,
            $this->oldTokens
        );

        // replace the 3 "fake" items that may be in the code (allowing for different indentation)
        $newCode = preg_replace('/(\ |\t)*private\ \$__EXTRA__LINE;/', '', $newCode);
        $newCode = preg_replace('/use __EXTRA__LINE;/', '', $newCode);
        $newCode = preg_replace('/(\ |\t)*\$__EXTRA__LINE;/', '', $newCode);

        $this->setSourceCode($newCode);
    }

    /**
     * @param string $doctrineType
     * @return string|null
     */
    public function getEntityTypeHint(string $doctrineType): ?string
    {
        switch ($doctrineType) {
            case 'string':
            case 'text':
            case 'guid':
            case 'bigint':
            case 'decimal':
                return 'string';

            case 'array':
            case 'simple_array':
            case 'json':
            case 'json_array':
                return 'array';

            case 'boolean':
                return 'bool';

            case 'integer':
            case 'smallint':
                return 'int';

            case 'float':
                return 'float';

            case 'datetime':
            case 'datetimetz':
            case 'date':
            case 'time':
                return '\\'.\DateTimeInterface::class;

            case 'datetime_immutable':
            case 'datetimetz_immutable':
            case 'date_immutable':
            case 'time_immutable':
                return '\\'.\DateTimeImmutable::class;

            case 'dateinterval':
                return '\\'.\DateInterval::class;

            case 'object':
            case 'binary':
            case 'blob':
            default:
                return null;
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @return string
     */
    private function getKeyValuePairStr(string $key, string $value): string
    {
        return sprintf('%s=%s', $key, $this->quoteValue($value));
    }

    /**
     * @param string $value
     * @return string
     */
    private function quoteValue(string $value): string
    {
        if ($value === 'true' || $value === 'false') {
            return $value;
        }

        if (\is_numeric($value)) {
            return $value;
        }

        return sprintf('"%s"', $value);
    }

    /**
     * @param callable $filterCallback
     * @return Node|null
     */
    private function findFirstNode(callable $filterCallback): ?Node
    {
        $traverser = new NodeTraverser();
        $visitor = new NodeVisitor\FirstFindingVisitor($filterCallback);
        $traverser->addVisitor($visitor);
        $traverser->traverse($this->newStmts);

        return $visitor->getFoundNode();
    }

    /**
     * @param callable $filterCallback
     * @return Node[]
     */
    private function findAllNodes(callable $filterCallback): array
    {
        $traverser = new NodeTraverser();
        $visitor = new NodeVisitor\FindingVisitor($filterCallback);
        $traverser->addVisitor($visitor);
        $traverser->traverse($this->newStmts);

        return $visitor->getFoundNodes();
    }

    private function setSourceCode(string $sourceCode): void
    {
        $this->sourceCode = $sourceCode;
        $this->oldStmts = $this->parser->parse($sourceCode);
        $this->oldTokens = $this->lexer->getTokens();

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new NodeVisitor\CloningVisitor());
        $traverser->addVisitor(new NodeVisitor\NameResolver(null, [
            'replaceNodes' => false,
        ]));
        $this->newStmts = $traverser->traverse($this->oldStmts);
    }
}
