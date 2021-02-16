<?php

namespace App\Form;

use App\Entity\Experience;
use App\Form\Type\DatePickerType;
use App\Form\Type\DropzoneType;
use App\Service\ImageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', TextType::class, [
                'required' => true,
                'label' => 'Empresa *',
                'attr' => [
                    'placeholder' => 'Nombre de la empresa'
                ]
            ])
            ->add('position', TextType::class, [
                'required' => true,
                'label' => 'Posición *',
                'attr' => [
                    'placeholder' => 'Puesto desarrollado en la empresa'
                ]
            ])
            ->add('monthYearStarted', DatePickerType::class, [
                'required' => true,
                'label' => 'Mes y año de comienzo *',
                'attr' => [
                    'data-format' => 'm/Y',
                    'placeholder' => 'Seleccione un mes y año'
                ],
                'help' => 'Seleccione una fecha cualquiera del mes y año deseado'
            ])
            ->add('monthYearEnded', DatePickerType::class, [
                'required' => false,
                'label' => 'Mes y año de finalización',
                'attr' => [
                    'data-format' => 'm/Y',
                    'placeholder' => 'Seleccione un mes y año'
                ],
                'help' => 'Seleccione una fecha cualquiera del mes y año deseado'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Descripción',
                'attr' => [
                    'placeholder' => 'Redacta una descripción sobre lo aprendido'
                ]
            ])
        ;

        $imageTypes = $this->imageService->getTypesInfo('experience');
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
            'data_class' => Experience::class,
        ]);
    }
}
