<?php

namespace App\Library\Mail;

abstract class AbstractMail
{
    /** @var string */
    protected $to;

    /** @var string */
    protected $subject;

    /** @var string */
    protected $template;

    /** @var array */
    protected $parameters;

    /**
     * @param string $to
     * @param array $parameters
     */
    public function prepare(string $to, array $parameters): void
    {
        $this->to = $to;
        $this->parameters = $parameters;
        $this->subject = $this->getMailSubject();
        $this->template = $this->getMailTemplate();
    }

    /**
     * @return string
     */
    abstract protected function getMailTemplate(): string;

    /**
     * @return string
     */
    abstract protected function getMailSubject(): string;

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }
}
