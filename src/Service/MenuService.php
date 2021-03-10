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
    private $contextService;

    public function __construct(Security $security, RequestStack $requestStack, ContextService $contextService)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->contextService = $contextService;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getMenu(): array
    {
        return $this->contextService->isCMS() ? $this->getCmsMenu() : $this->getSiteMenu();
    }

    /**
     * @return MenuItem[]
     * @throws \Exception
     */
    private function getSiteMenu(): array
    {
        $aboutItem = new MenuItem(
            'Sobre mí',
            '',
            $this->isActive('about'),
            'site_about_index',
            'IS_AUTHENTICATED_ANONYMOUSLY',
            ''
        );
        $blogItem = new MenuItem(
            'Blog',
            '',
            $this->isActive('blog'),
            'site_blog_index',
            'IS_AUTHENTICATED_ANONYMOUSLY',
            ''
        );
        $contactItem = new MenuItem(
            'Contacto',
            '',
            $this->isActive('contact'),
            'site_contact_form',
            'IS_AUTHENTICATED_ANONYMOUSLY',
            ''
        );

        return [$aboutItem, $blogItem, $contactItem];
    }

    /**
     * @return MenuGroup[]
     * @throws \Exception
     */
    private function getCmsMenu(): array
    {
        $config = $this->getCmsMenuConfig();
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
     * @SuppressWarnings(PHPMD)
     * @return MenuGroup[]
     * @throws \Exception
     */
    private function getCmsMenuConfig(): array
    {
        $dashboardItem = new MenuItem(
            'Dashboard',
            '',
            $this->isActive('dashboard'),
            'cms_dashboard_index',
            'ROLE_USER',
            'fas fa-tachometer-alt'
        );
        $contactItem = new MenuItem(
            'Contactos',
            '',
            $this->isActive('contact'),
            'cms_contact_index',
            'ROLE_ADMIN',
            'fas fa-envelope'
        );
        $generalGroup = new MenuGroup('General', [$dashboardItem, $contactItem]);

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
            $this->isActive('tag'),
            'cms_tag_index',
            'ROLE_EDITOR',
            'fas fa-tags'
        );
        $editorialGroup = new MenuGroup('Editorial', [$postsItem, $categoriesItem, $tagsItem]);

        $profileItem = new MenuItem(
            'Perfil',
            '',
            $this->isActive('profile'),
            'cms_profile_index',
            'ROLE_ADMIN',
            'fas fa-address-card'
        );
        $certificationsItem = new MenuItem(
            'Certificaciones',
            '',
            $this->isActive('certification'),
            'cms_certification_index',
            'ROLE_ADMIN',
            'fas fa-award'
        );
        $educationsItem = new MenuItem(
            'Educaciones',
            '',
            $this->isActive('education'),
            'cms_education_index',
            'ROLE_ADMIN',
            'fas fa-university'
        );
        $experiencesItem = new MenuItem(
            'Experiencias',
            '',
            $this->isActive('experience'),
            'cms_experience_index',
            'ROLE_ADMIN',
            'fas fa-briefcase'
        );
        $projectsItem = new MenuItem(
            'Proyectos',
            '',
            $this->isActive('project'),
            'cms_project_index',
            'ROLE_ADMIN',
            'fas fa-clipboard'
        );
        $skillGroupsItem = new MenuItem(
            'Habilidades',
            '',
            $this->isActive('skillgroup'),
            'cms_skillgroup_index',
            'ROLE_ADMIN',
            'fas fa-hammer'
        );
        $personalGroup = new MenuGroup(
            'Personal',
            [$profileItem, $educationsItem, $certificationsItem, $experiencesItem, $projectsItem, $skillGroupsItem]
        );

        return [$generalGroup, $editorialGroup, $personalGroup];
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
        return explode('_', $route)[1];
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
