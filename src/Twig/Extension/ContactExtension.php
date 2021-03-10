<?php

namespace App\Twig\Extension;

use App\Entity\Contact;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ContactExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_contact_status', [$this, 'getContactStatus'])
        ];
    }

    /**
     * @param Contact $contact
     * @return string[]
     * @throws \Exception
     */
    public function getContactStatus(Contact $contact): array
    {
        switch ($contact->getStatus()) {
            case Contact::STATUS_UNREAD:
                return [
                    'label' => 'Sin leer',
                    'badge' => 'warning'
                ];
            case Contact::STATUS_READ:
                return [
                    'label' => 'Leído',
                    'badge' => 'info'
                ];
            default:
                throw new \Exception(sprintf('Status %s not handled!', $contact->getStatus()));
        }
    }
}
