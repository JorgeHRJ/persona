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
     * @Assert\NotBlank(message="El año y mes de comienzo no puede estar vacío")
     * @Assert\Length(min=7, max=7, maxMessage="El año y mes de comienzo debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="experience_monthyear_started", type="string", length=7, nullable=false)
     */
    private $monthYearStarted;

    /**
     * @var string|null
     *
     * @Assert\Length(min=7, max=7, maxMessage="El año de finalización debe tener {{ limit }} caracteres")
     *
     * @ORM\Column(name="experience_monthyear_ended", type="string", length=7, nullable=true)
     */
    private $monthYearEnded;

    /**
     * @var string|null
     *
     * @ORM\Column(name="experience_description", type="text", nullable=true)
     */
    private $description;

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

    public function getMonthYearStarted(): ?string
    {
        return $this->monthYearStarted;
    }

    public function setMonthYearStarted(string $monthYearStarted): self
    {
        $this->monthYearStarted = $monthYearStarted;

        return $this;
    }

    public function getMonthYearEnded(): ?string
    {
        return $this->monthYearEnded;
    }

    public function setMonthYearEnded(?string $monthYearEnded): self
    {
        $this->monthYearEnded = $monthYearEnded;

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
