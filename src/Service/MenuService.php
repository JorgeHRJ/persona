<?php

namespace App\Service;

use App\Entity\User;
use App\Library\Model\MenuGroup;
use App\Library\Model\MenuItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class MenuService
{
    private $security;
    private $requestStack;

    public function __construct(Security $security, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    /**
     * @return MenuGroup[]
     * @throws \Exception
     */
    public function getMenu(): array
    {
        $config = $this->getMenuConfig();
        $menu = [];

        foreach ($config as $menuGroup) {
            $menuItems = $menuGroup->getItems();
            $allowedItems = [];
            foreach ($menuItems as $menuItem) {
                $role = $menuItem->getRole();
                if ($this->security->isGranted($role)) {
                    $allowedItems[] = $menuItem;
                }
            }

            if (!empty($allowedItems)) {
                $menuGroup->setItems($allowedItems);
                $menu[] = $menuGroup;
            }
        }

        return $menu;
    }

    /**
     * @return MenuGroup[]
     * @throws \Exception
     */
    private function getMenuConfig(): array
    {
        $dashboardItem = new MenuItem(
            'Dashboard',
            '',
            $this->isActive('dashboard'),
            'cms_dashboard_index',
            'ROLE_USER',
            'fas fa-tachometer-alt'
        );
        $generalGroup = new MenuGroup('General', [$dashboardItem]);

        $postsItem = new MenuItem(
            'Artículos',
            '',
            $this->isActive('post'),
            'cms_post_index',
            'ROLE_EDITOR',
            'fas fa-pen-fancy'
        );
        $categoriesItem = new MenuItem(
            'Categorías',
            '',
            $this->isActive('category'),
            'cms_category_index',
            'ROLE_EDITOR',
            'far fa-folder-open'
        );
        $tagsItem = new MenuItem(
            'Etiquetas',
            '',
            $this->isActive('post'),
            'cms_tag_index',
            'ROLE_EDITOR',
            'fas fa-tags'
        );
        $editorialGroup = new MenuGroup('Editorial', [$postsItem, $categoriesItem, $tagsItem]);

        return [$generalGroup, $editorialGroup];
    }

    /**
     * @param string $entity
     * @return bool
     * @throws \Exception
     */
    private function isActive(string $entity): bool
    {
        return strpos($this->getEntityFromRouteName(), $entity) !== false;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function getEntityFromRouteName(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            throw new \Exception('Not request found');
        }

        $route = $request->attributes->get('_route');
        return explode('_', $route)[0];
    }

    /**
     * @return User
     */
    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}
