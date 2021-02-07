<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\Type\DateTimePickerType;
use App\Form\Type\EditorType;
use App\Form\Type\TagsInputType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostType extends AbstractType
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Título *',
                'attr' => ['autofocus' => true, 'placeholder' => 'Título']
            ])
            ->add('content', EditorType::class, [
                'required' => true,
                'label' => 'Contenido *',
            ])
            ->add('publishedAt', DateTimePickerType::class, [
                'required' => true,
                'label' => 'Fecha de publicación *',
                'help' => 'Selecciona una fecha de publicación. A partir de esta fecha estará publicado el artículo',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => true,
                'label' => 'Categoría *',
                'placeholder' => 'Seleccione una categoría'
            ])
            ->add('tags', TagsInputType::class, [
                'required' => false,
                'label' => 'Etiquetas'
            ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Post $post */
            $post = $event->getData();
            if ($post->getTitle() !== null) {
                $post->setSlug($this->slugger->slug($post->getTitle())->lower());
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
