<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[UniqueEntity('alias')]
#[ApiResource(
    collectionOperations:
    [
        'get' =>
        [
            'normalization_context' => [ 'groups' => 'list' ],
        ],
        'post' =>
        [
            'normalization_context' => [ 'groups' => 'list' ],
        ],
    ],
    itemOperations:
    [
        'get' =>
        [
            'normalization_context' => [ 'groups' => 'item' ],
        ],
        'put' =>
        [
            'normalization_context' => [ 'groups' => 'item' ],
        ],
        'delete' =>
        [
            'normalization_context' => [ 'groups' => 'item' ],
        ],
    ],
    order: [ 'publish_date' => 'DESC' ],
    paginationEnabled: true,
)]
class Post
{
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DRAFT     = 'draft';
    public const STATUSES         = [ self::STATUS_PUBLISHED, self::STATUS_DRAFT ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['list', 'item'])]
    private $id;

    #[ORM\Column(type: 'string', length: 128)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 128
    )]
    #[Groups(['list', 'item'])]
    private $title;

    #[ORM\Column(type: 'string', length: 128, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 128
    )]
    #[Assert\Regex('/^[a-z0-9\-]+$/')]
    #[Groups(['list', 'item'])]
    private $alias;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Groups(['item'])]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    #[Groups(['list', 'item'])]
    private $publish_date;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['list', 'item'])]
    private $author;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['list', 'item'])]
    private $category;

    #[ORM\Column(type: 'string', length: 32, options: ['default' => self::STATUS_PUBLISHED])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: self::STATUSES)]
    #[Groups(['list', 'item'])]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(\DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
