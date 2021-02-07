<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimePickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['data-component'] = 'datepicker';
        $view->vars['attr']['placeholder'] = 'Seleccione una fecha';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'html5' => false,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy HH:ii:ss',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return DateTimeType::class;
    }
}
