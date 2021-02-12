<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ORM\Table(name="project")
 */
class Project
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="project_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El nombre no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El nombre no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="project_name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @Assert\Length(max=64, maxMessage="El tipo no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="project_type", type="string", length=64, nullable=true)
     */
    private $type;

    /**
     * @var string|null
     *
     * @Assert\Length(min=4, max=4, maxMessage="El año debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="project_year", type="string", length=4, nullable=true)
     */
    private $year;

    /**
     * @var string|null
     *
     * @ORM\Column(name="project_description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @Assert\Length(max=128, maxMessage="El enlace de demo debe tener {{ limit }} caracteres")
     * @Assert\Url(message="El enlace de demo debe tener un formato de URL correcto")
     *
     * @ORM\Column(name="project_demo", type="string", length=128, nullable=true)
     */
    private $demo;

    /**
     * @var Asset|null
     *
     * @ORM\OneToOne(targetEntity=Asset::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="project_image", referencedColumnName="asset_id")
     */
    private $image;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="project_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="project_modified_at", type="datetime", nullable=true)
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

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

    public function getDemo(): ?string
    {
        return $this->demo;
    }

    public function setDemo(string $demo): self
    {
        $this->demo = $demo;

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
