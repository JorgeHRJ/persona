<?php

namespace App\Twig\Extension;

use App\Service\MenuService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_menu', [$this, 'getMenu'])
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getMenu(): array
    {
        return $this->menuService->getMenu();
    }
}
