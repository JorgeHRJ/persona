<?php

namespace App\Twig\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UtilsExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_filter_query', [$this, 'getFilterQuery']),
            new TwigFunction('get_form_children', [$this, 'getFormChildren']),
            new TwigFunction('get_site', [$this, 'getSite']),
            new TwigFunction('sanitize_sitemap_loc', [$this, 'sanitizeSitemapLoc'])
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

    public function getSite(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return '';
        }

        return $request->getSchemeAndHttpHost();
    }

    public function sanitizeSitemapLoc(string $loc): string
    {
        return substr($loc, -1) === '/' ? $loc : sprintf('%s/', $loc);
    }
}
