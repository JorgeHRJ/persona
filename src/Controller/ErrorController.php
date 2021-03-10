<?php

namespace App\Controller;

use App\Service\ContextService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class ErrorController
{
    private $requestStack;
    private $templating;

    public function __construct(RequestStack $requestStack, Environment $templating)
    {
        $this->requestStack = $requestStack;
        $this->templating = $templating;
    }

    public function show(\Throwable $exception): Response
    {
        $code = $exception instanceof NotFoundHttpException ? 404 : 500;

        $request = $this->requestStack->getCurrentRequest();
        $context = strpos($request->getPathInfo(), '/cms') !== false ? 'cms' : 'site';

        return new Response(
            $this->templating->render(sprintf('%s/error/%d.html.twig', $context, $code))
        );
    }
}
