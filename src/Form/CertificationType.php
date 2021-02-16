<?php

namespace App\Form;

use App\Entity\Certification;
use App\Form\Type\DatePickerType;
use App\Form\Type\DropzoneType;
use App\Service\ImageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificationType extends AbstractType
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organization', TextType::class, [
                'required' => true,
                'label' => 'Organización *',
                'attr' => [
                    'placeholder' => 'Nombre de la organización que acredita la certificación'
                ]
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nombre *',
                'attr' => [
                    'placeholder' => 'Nombre de la certificación'
                ]
            ])
            ->add('yearStarted', DatePickerType::class, [
                'required' => true,
                'label' => 'Año de comienzo *'
            ])
            ->add('yearEnded', DatePickerType::class, [
                'required' => false,
                'label' => 'Año de finalización'
            ])
        ;

        $imageTypes = $this->imageService->getTypesInfo('certification');
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
            'data_class' => Certification::class,
        ]);
    }
}
