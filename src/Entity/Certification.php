<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CertificationRepository::class)
 * @ORM\Table(name="certification")
 */
class Certification
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="certification_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El nombre de la certificación no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El nombre de la certificación no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="certification_name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El año de comienzo no puede estar vacío")
     * @Assert\Length(min=4, max=4, maxMessage="El año de comienzo debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="certification_year_started", type="string", length=4, nullable=false)
     */
    private $yearStarted;

    /**
     * @var string|null
     *
     * @Assert\Length(min=4, max=4, maxMessage="El año de finalización debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="certification_year_ended", type="string", length=4, nullable=true)
     */
    private $yearEnded;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="certification_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="certification_modified_at", type="datetime", nullable=true)
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYearStarted(): ?string
    {
        return $this->yearStarted;
    }

    public function setYearStared(\DateTimeInterface $yearStarted): self
    {
        $this->yearStarted = $yearStarted;

        return $this;
    }

    public function getYearEnded(): ?string
    {
        return $this->yearEnded;
    }

    public function setYearEnded(?string $yearEnded): self
    {
        $this->yearEnded = $yearEnded;

        return $this;
    }
}
