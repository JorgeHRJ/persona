<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use App\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 * @ORM\Table(name="profile")
 */
class Profile
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="profile_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var User|null
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="profile_user", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_twitter", type="string", length=64, nullable=true)
     */
    private $twitter;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_linkedin", type="string", length=64, nullable=true)
     */
    private $linkedin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_github", type="string", length=64, nullable=true)
     */
    private $github;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_instagram", type="string", length=64, nullable=true)
     */
    private $instagram;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_stackoverflow", type="string", length=64, nullable=true)
     */
    private $stackoverflow;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El título no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El título no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="profile_title", type="string", length=128, nullable=true)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_presentation", type="text", nullable=true)
     */
    private $presentation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_skills_summary", type="text", nullable=true)
     */
    private $skillsSummary;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="profile_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="profile_modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getStackoverflow(): ?string
    {
        return $this->stackoverflow;
    }

    public function setStackoverflow(?string $stackoverflow): self
    {
        $this->stackoverflow = $stackoverflow;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getSkillsSummary(): ?string
    {
        return $this->skillsSummary;
    }

    public function setSkillsSummary(?string $skillsSummary): self
    {
        $this->skillsSummary = $skillsSummary;

        return $this;
    }
}
