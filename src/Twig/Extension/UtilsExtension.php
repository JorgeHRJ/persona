<?php

namespace App\Twig\Extension;

use Symfony\Component\Form\FormView;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UtilsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_filter_query', [$this, 'getFilterQuery']),
            new TwigFunction('get_form_children', [$this, 'getFormChildren'])
        ];
    }

    /**
     * @param array $queryParams
     * @param array $filters
     * @return string
     */
    public function getFilterQuery(array $queryParams, array $filters)
    {
        $allowedParams = array_intersect_key($queryParams, array_flip($filters));

        return sprintf('&%s', http_build_query($allowedParams));
    }

    /**
     * @param FormView $form
     * @param string $var
     * @return FormView
     */
    public function getFormChildren(FormView $form, string $var): FormView
    {
        return $form->children[$var];
    }
}
