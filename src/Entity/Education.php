<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EducationRepository::class)
 * @ORM\Table(name="education")
 */
class Education
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="education_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El nombre de la organización no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El nombre de la organización no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="education_organization", type="string", length=128, nullable=false)
     */
    private $organization;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El título no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El título no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="education_title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El año de comienzo no puede estar vacío")
     * @Assert\Length(min=4, max=4, maxMessage="El año de comienzo debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="education_year_started", type="string", length=4, nullable=false)
     */
    private $yearStarted;

    /**
     * @var string|null
     *
     * @Assert\Length(min=4, max=4, maxMessage="El año de finalización debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="education_year_ended", type="string", length=4, nullable=true)
     */
    private $yearEnded;

    /**
     * @var string|null
     *
     * @ORM\Column(name="education_description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="education_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="education_modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getYearStarted(): ?string
    {
        return $this->yearStarted;
    }

    public function setYearStarted(string $yearStarted): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
