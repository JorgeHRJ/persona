<?php

namespace App\Entity;

use App\Library\Traits\Entity\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\Table(name="post")
 * @UniqueEntity(fields={"slug"}, errorPath="title", message="Hay un artículo con ese título")
 */
class Post
{
    use TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     *
     * @ORM\Column(name="post_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El título del artículo no puede estar vacío")
     * @Assert\Length(max=128, maxMessage="El título no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="post_title", type="string", length=128, nullable=false)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="post_slug", type="string", nullable=false)
     */
    private $slug;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El resumen del artículo no puede estar vacío")
     * @Assert\Length(max=255, maxMessage="El resumen del artículo no puede superar los {{ limit }} caracteres")
     *
     * @ORM\Column(name="post_summary", type="string", length=255, nullable=false)
     */
    private $summary;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="El contenido del artículo no puede estar vacío")
     *
     * @ORM\Column(name="post_content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var int|null
     *
     * @ORM\Column(name="post_read_time", type="integer", nullable=false)
     */
    private $readTime;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="post_published_at", type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="post_author", referencedColumnName="user_id", nullable=false)
     */
    private $author;

    /**
     * @var Category|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="posts")
     * @ORM\JoinColumn(name="post_category", referencedColumnName="category_id", nullable=false)
     */
    private $category;

    /**
     * @var Tag[]|Collection
     *
     * @Assert\Count(max="5", maxMessage="Un artículo solo puede tener un máximo de 5 etiquetas")
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="posts", cascade={"persist"})
     * @ORM\JoinTable(name="post_tag",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="post_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="tag_id")}
     * )
     */
    private $tags;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="post_created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="post_modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getReadTime(): ?int
    {
        return $this->readTime;
    }

    public function setReadTime(int $readTime): self
    {
        $this->readTime = $readTime;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPost($this);
        }
    }

    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePost($this);
        }
    }

    public function getTags(): ?Collection
    {
        return $this->tags;
    }
}
