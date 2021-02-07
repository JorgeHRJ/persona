<?php

namespace App\Form\Type;

use App\Form\DataTransformer\TagArrayToStringTransformer;
use App\Service\Cms\TagService;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TagsInputType extends AbstractType
{
    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['placeholder'] = 'Separe las etiquetas por comas (etiqueta1, etiqueta2, ...)';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new TagArrayToStringTransformer($this->tagService), true)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TextType::class;
    }
}
