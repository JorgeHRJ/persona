<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tag")
 */
class Tag
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="tag_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El nombre de la etiqueta no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El nombre de la etiqueta no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="tag_name", type="string", length=128, unique=true, nullable=false)
     */
    private $name;

    /**
     * @var Post[]|Collection
     *
     * @ORM\ManyToMany(targetEntity=Post::class, inversedBy="tags", cascade={"persist"})
     * @ORM\JoinTable(name="post_tag",
     *      joinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="tag_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="post_id")}
     * )
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="tag_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="tag_modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
        }

        return $this;
    }
}
