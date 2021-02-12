<?php

namespace App\Form;

use App\Entity\Education;
use App\Form\Type\DatePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EducationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organization', TextType::class, [
                'required' => true,
                'label' => 'Organización *',
                'attr' => [
                    'placeholder' => 'Nombre de la organización que impartía la educación'
                ]
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Título *',
                'attr' => [
                    'placeholder' => 'Título de la educación impartida'
                ]
            ])
            ->add('yearStarted', DatePickerType::class, [
                'required' => true,
                'label' => 'Año de comienzo *',
                'attr' => [
                    'data-format' => 'Y',
                    'placeholder' => 'Seleccione un año'
                ],
                'help' => 'Seleccione una fecha cualquiera del año deseado'
            ])
            ->add('yearEnded', DatePickerType::class, [
                'required' => false,
                'label' => 'Año de finalización',
                'attr' => [
                    'data-format' => 'Y',
                    'placeholder' => 'Seleccione un año'
                ],
                'help' => 'Seleccione una fecha cualquiera año deseado'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Descripción',
                'attr' => [
                    'placeholder' => 'Redacta una descripción sobre lo aprendido'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Education::class,
        ]);
    }
}
