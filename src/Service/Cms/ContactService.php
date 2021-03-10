<?php

namespace App\Service\Cms;

use App\Entity\Contact;
use App\Library\Repository\BaseRepository;
use App\Library\Service\BaseService;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ContactService extends BaseService
{
    const CACHE_EXPIRATION = 3600;
    const UNREAD_CONTACTS_KEY = 'unread_contacts';

    private $cache;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, CacheInterface $cache)
    {
        parent::__construct($entityManager, $logger);
        $this->cache = $cache;
    }

    public function updateStatus(Contact $contact, int $status): void
    {
        $contact->setStatus($status);
        $this->entityManager->flush();

        $this->cache->delete(self::UNREAD_CONTACTS_KEY);
    }

    public function countUnread(): int
    {
        return $this->cache->get(self::UNREAD_CONTACTS_KEY, function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            return $this->getRepository()->countUnread();
        });
    }

    public function getSortFields(): array
    {
        return [];
    }

    public function getEntityClass(): string
    {
        return Contact::class;
    }

    /**
     * @return ContactRepository
     */
    public function getRepository(): BaseRepository
    {
        return $this->repository;
    }
}
