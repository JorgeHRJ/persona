<?php

namespace App\Form;

use App\Entity\Project;
use App\Form\Type\DatePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nombre *',
                'attr' => [
                    'placeholder' => 'Nombre del proyecto'
                ]
            ])
            ->add('type', TextType::class, [
                'required' => false,
                'label' => 'Tipo de proyecto',
                'attr' => [
                    'placeholder' => 'Por ejemplo: proyecto personal, proyecto educativo, etc.'
                ]
            ])
            ->add('year', DatePickerType::class, [
                'required' => true,
                'label' => 'Año de comienzo *',
                'attr' => [
                    'data-format' => 'Y',
                    'placeholder' => 'Seleccione un año'
                ],
                'help' => 'Seleccione una fecha cualquiera del año deseado'
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Descripción',
                'attr' => [
                    'placeholder' => 'Descripción del proyecto realizado'
                ]
            ])
            ->add('demo', UrlType::class, [
                'required' => false,
                'label' => 'Enlace de demo',
                'attr' => [
                    'placeholder' => 'Puedes dejar un enlace a una demo del proyecto'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
