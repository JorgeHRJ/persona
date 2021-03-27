<?php

namespace App\Library\Mail;

class ContactReceivedMail extends AbstractMail
{
    /**
     * @return string
     */
    protected function getMailTemplate(): string
    {
        return 'cms/mail/contact_received.html.twig';
    }

    /**
     * @return string
     */
    protected function getMailSubject(): string
    {
        return 'Nuevo contacto';
    }
}
