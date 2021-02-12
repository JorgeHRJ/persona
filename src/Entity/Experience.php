<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 * @ORM\Table(name="experience")
 */
class Experience
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="experience_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="La posición no puede estar vacía")
     * @Assert\Length(max=128, maxMessage="La posición no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="experience_position", type="string", length=128, nullable=false)
     */
    private $position;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="La empresa no puede estar vacía")
     * @Assert\Length(max=128, maxMessage="La posición no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="experience_company", type="string", length=128, nullable=false)
     */
    private $company;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El año de comienzo no puede estar vacío")
     * @Assert\Length(min=4, max=4, maxMessage="El año de comienzo debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="experience_year_started", type="string", length=4, nullable=false)
     */
    private $yearStarted;

    /**
     * @var string|null
     *
     * @Assert\Length(min=4, max=4, maxMessage="El año de finalización debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="experience_year_ended", type="string", length=4, nullable=true)
     */
    private $yearEnded;

    /**
     * @var string|null
     *
     * @ORM\Column(name="experience_description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Asset|null
     *
     * @ORM\OneToOne(targetEntity=Asset::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="experience_image", referencedColumnName="asset_id")
     */
    private $image;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="experience_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="experience_modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?Asset
    {
        return $this->image;
    }

    public function setImage(?Asset $image): self
    {
        $this->image = $image;

        return $this;
    }
}
