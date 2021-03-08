<?php

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nombre *',
                'attr' => ['placeholder' => 'Nombre de la habilidad']
            ])
            ->add('icon', TextType::class, [
                'required' => false,
                'label' => 'Icono',
                'attr' => ['placeholder' => 'Icono de la habilidad'],
                'help' => '
                Inserta un icono de la librería de FontAwesome (https://fontawesome.com/icons?s=brands).
                Por ejemplo: "fab fa-symfony"
                '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
