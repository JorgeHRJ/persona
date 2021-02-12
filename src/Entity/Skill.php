<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use App\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 * @ORM\Table(name="skill")
 */
class Skill
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="skill_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El nombre no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El nombre no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="skill_name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="skill_icon", type="string", length=128, nullable=true)
     */
    private $icon;

    /**
     * @var SkillGroup|null
     *
     * @ORM\ManyToOne(targetEntity=SkillGroup::class, inversedBy="skills")
     * @ORM\JoinColumn(name="skill_group", referencedColumnName="skillgroup_id", nullable=false)
     */
    private $group;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="skill_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="skill_modified_at", type="datetime", nullable=true)
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getGroup(): ?SkillGroup
    {
        return $this->group;
    }

    public function setGroup(?SkillGroup $group): self
    {
        $this->group = $group;

        return $this;
    }
}
