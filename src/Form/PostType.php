<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\Type\DateTimePickerType;
use App\Form\Type\DropzoneType;
use App\Form\Type\EditorType;
use App\Form\Type\TagsInputType;
use App\Service\ImageService;
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
    private $imageService;
    private $slugger;

    public function __construct(ImageService $imageService, SluggerInterface $slugger)
    {
        $this->imageService = $imageService;
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
                'attr' => [
                    'placeholder' => 'Redacta aquí el contenido del artículo'
                ]
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

        $imageTypes = $this->imageService->getTypesInfo('post');
        foreach ($imageTypes as $type => $info) {
            $builder->add($type, DropzoneType::class, [
                'label' => $info['title'],
                'attr' => [
                    'data-size' => $info['size']
                ]
            ]);
        }

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
