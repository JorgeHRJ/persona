<?php

namespace App\Form;

use App\Entity\Profile;
use App\Form\Type\DropzoneType;
use App\Service\ImageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    const ACCOUNT_PLACEHOLDER = 'Escribe tu nombre de usuario o la URL directamente';

    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('twitter', TextType::class, [
                'required' => false,
                'label' => 'Twitter',
                'attr' => [
                    'placeholder' => self::ACCOUNT_PLACEHOLDER
                ]
            ])
            ->add('linkedin', TextType::class, [
                'required' => false,
                'label' => 'LinkedIn',
                'attr' => [
                    'placeholder' => self::ACCOUNT_PLACEHOLDER
                ]
            ])
            ->add('github', TextType::class, [
                'required' => false,
                'label' => 'GitHub',
                'attr' => [
                    'placeholder' => self::ACCOUNT_PLACEHOLDER
                ]
            ])
            ->add('instagram', TextType::class, [
                'required' => false,
                'label' => 'Instagram',
                'attr' => [
                    'placeholder' => self::ACCOUNT_PLACEHOLDER
                ]
            ])
            ->add('stackoverflow', TextType::class, [
                'required' => false,
                'label' => 'StackOverflow',
                'attr' => [
                    'placeholder' => self::ACCOUNT_PLACEHOLDER
                ]
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Título personal *',
                'attr' => [
                    'placeholder' => 'Escribe a qué te dedicas (ejemplo: "Desarrollador software")'
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'required' => false,
                'label' => 'Presentación',
                'attr' => [
                    'placeholder'
                        => 'Redacta una presentación personal con lo que desees: qué haces, tus aficiones, etc.'
                ]
            ])
            ->add('skillsSummary', TextareaType::class, [
                'required' => false,
                'label' => 'Habilidades',
                'attr' => [
                    'placeholder' => 'Escribe un resumen sobre tus habilidades/capacidades personales en tu área'
                ]
            ])
        ;

        $imageTypes = $this->imageService->getTypesInfo('profile');
        foreach ($imageTypes as $type => $info) {
            $builder->add($type, DropzoneType::class, [
                'label' => $info['title'],
                'attr' => [
                    'data-size' => $info['size']
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
