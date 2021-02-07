<?php

namespace App\Form\Type;

use App\Form\DataTransformer\PostContentArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class EditorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new PostContentArrayToStringTransformer(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return HiddenType::class;
    }
}
