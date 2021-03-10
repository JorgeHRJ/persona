<?php

namespace App\Form;

use App\Entity\Contact;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact_form',
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nombre *',
                'attr' => [
                    'placeholder' => 'Tu nombre *',
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Email *',
                ]
            ])
            ->add('message', TextareaType::class, [
                'required' => false,
                'label' => 'Mensaje *',
                'attr' => [
                    'placeholder' => 'Mensaje a enviar *',
                ]
            ])
            ->add('acceptance', CheckboxType::class, [
                'required' => false,
                'label' => '¿Aceptas que se guarde tu información de contacto para ser contactado en el futuro?',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
