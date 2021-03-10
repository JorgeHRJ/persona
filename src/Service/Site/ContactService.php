<?php

namespace App\Service\Site;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ContactService
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function create(Contact $contact): void
    {
        $contact->setStatus(Contact::STATUS_UNREAD);

        try {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Contact was not created from site due to error: %s', $e->getMessage()));
        }
    }
}
