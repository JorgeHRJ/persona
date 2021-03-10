<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ORM\Table(name="contact")
 */
class Contact
{
    use TimestampableTrait;

    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;
    const STATUS_TYPES = [self::STATUS_UNREAD, self::STATUS_READ];

    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     *
     * @ORM\Column(name="contact_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El nombre no debe estar vacío")
     * @Assert\Length(max=128, maxMessage="El nombre no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="contact_name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El email no debe estar vacío")
     * @Assert\Length(max=128, maxMessage="El email no puede superar los {{ limit }} caracteres")
     * @Assert\Email(message="El email no tiene un formato de email correcto")
     *
     * @ORM\Column(name="contact_email", type="string", length=128, nullable=false)
     */
    private $email;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El mensaje no debe estar vacío")
     *
     * @ORM\Column(name="contact_message", type="text", nullable=false)
     */
    private $message;

    /**
     * @var int|null
     *
     * @ORM\Column(name="contact_status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="contact_acceptance", type="boolean", nullable=false)
     */
    private $acceptance;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="contact_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="contact_modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAcceptance(): ?bool
    {
        return $this->acceptance;
    }

    public function setAcceptance(?bool $acceptance): self
    {
        $this->acceptance = $acceptance;

        return $this;
    }
}
