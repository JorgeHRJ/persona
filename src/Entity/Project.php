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
     * @ORM\Column(name="project_slug", type="string", nullable=false)
     */
    private $slug;

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
     * @Assert\Length(max=128, maxMessage="El resumen no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="project_summary", type="string", length=128, nullable=true)
     */
    private $summary;

    /**
     * @var string|null
     *
     * @ORM\Column(name="project_description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @Assert\Length(max=128, maxMessage="Las tecnologías usadas no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="project_stack", type="string", length=128, nullable=true)
     */
    private $stack;

    /**
     * @var string|null
     *
     * @Assert\Length(max=128, maxMessage="El enlace de demo no puede superar los {{ limit }} caracteres")
     * @Assert\Url(message="El enlace de demo debe tener un formato de URL correcto")
     *
     * @ORM\Column(name="project_demo", type="string", length=128, nullable=true)
     */
    private $demo;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

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

    public function getStack(): ?string
    {
        return $this->stack;
    }

    public function setStack(?string $stack): self
    {
        $this->stack = $stack;

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
}
