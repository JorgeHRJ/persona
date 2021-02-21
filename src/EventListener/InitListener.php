<?php

namespace App\EventListener;

use App\Service\ContextService;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class InitListener
{
    private $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $path = $event->getRequest()->getPathInfo();
        $context = strpos($path, '/cms') !== false ? ContextService::CMS : ContextService::SITE;
        $this->contextService->setContext($context);
    }
}
