<?php

namespace App\Service\Cms;

class StatsService
{
    const PUBLISHED_ARTICLES = 'published_articles';
    const UNPUBLISHED_ARTICLES = 'unpublished_articles';
    const PENDING_PUBLISH_ARTICLES = 'pending_publish_articles';
    const UNREAD_CONTACTS = 'unread_contacts';

    private $postService;
    private $contactService;

    public function __construct(PostService $postService, ContactService $contactService)
    {
        $this->postService = $postService;
        $this->contactService = $contactService;
    }

    public function getDashboard(): array
    {
        return [
            $this->getStat(self::PUBLISHED_ARTICLES),
            $this->getStat(self::UNPUBLISHED_ARTICLES),
            $this->getStat(self::PENDING_PUBLISH_ARTICLES),
            $this->getStat(self::UNREAD_CONTACTS),
        ];
    }

    /**
     * TODO Pasar esto a CompilerPass cuando se vuelva muy grande
     *
     * @param string $type
     * @return array
     * @throws \Exception
     */
    public function getStat(string $type): array
    {
        $result = ['type' => $type];
        $item = [];

        switch ($type) {
            case self::PUBLISHED_ARTICLES:
                $item['data'] = $this->postService->countPublished();
                $item['label'] = 'Artículos publicados';
                $item['icon'] = 'fas fa-calendar-check';
                break;
            case self::UNPUBLISHED_ARTICLES:
                $item['data'] = $this->postService->countUnpublished();
                $item['label'] = 'Artículos no publicados';
                $item['icon'] = 'fas fa-calendar-times';
                break;
            case self::PENDING_PUBLISH_ARTICLES:
                $item['data'] = $this->postService->countPendingPublish();
                $item['label'] = 'Artículos en borradores';
                $item['icon'] = 'fas fa-eraser';
                break;
            case self::UNREAD_CONTACTS:
                $item['data'] = $this->contactService->countUnread();
                $item['label'] = 'Contactos sin leer';
                $item['icon'] = 'fas fa-envelope';
                break;
            default:
                throw new \Exception(sprintf('Type %s not handled!', $type));
        }

        return array_merge($result, $item);
    }
}
