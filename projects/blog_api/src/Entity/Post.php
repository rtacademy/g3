<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity( repositoryClass: PostRepository::class )]
#[ORM\Table(name: '`post`')]
#[UniqueEntity( 'alias' )]
#[ApiResource(
    security: "is_granted('ROLE_API')",
    formats: [ 'json' ],
    collectionOperations: [
        'get'  =>
        [
            'normalization_context' => [ 'groups' => 'list' ],
        ],
        'post' =>
        [
            'normalization_context' => [ 'groups' => 'list' ],
        ],
    ],
    itemOperations: [
        'get'    =>
        [
            'normalization_context' => [ 'groups' => 'item' ],
        ],
        'put'    =>
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
    #[ORM\Column( type: Types::INTEGER )]
    #[Groups( [ 'list', 'item' ] )]
    private ?int $id = null;

    #[ORM\Column( type: Types::STRING, length: 128 )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 128
    )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $title = null;

    #[ORM\Column( type: Types::STRING, length: 128, unique: true )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 128
    )]
    #[Assert\Regex( '/^[a-z0-9\-]+$/' )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $alias = null;

    #[ORM\Column( type: Types::TEXT )]
    #[Assert\NotBlank]
    #[Groups( [ 'item' ] )]
    private ?string $content = null;

    #[ORM\Column( type: Types::DATETIME_MUTABLE )]
    #[Assert\NotBlank]
    #[Assert\Type( \DateTime::class )]
    #[Groups( [ 'list', 'item' ] )]
    private ?\DateTimeInterface $publish_date = null;

    #[ORM\ManyToOne( targetEntity: User::class )]
    #[ORM\JoinColumn( nullable: false )]
    #[Groups( [ 'list', 'item' ] )]
    private ?User $author = null;

    #[ORM\ManyToOne( targetEntity: PostCategory::class )]
    #[ORM\JoinColumn( nullable: false )]
    #[Groups( [ 'list', 'item' ] )]
    private ?PostCategory $category = null;

    #[ORM\ManyToOne( targetEntity: PostCover::class )]
    #[ORM\JoinColumn(nullable: false)]
    private ?PostCover $cover = null;

    #[ORM\Column( type: Types::STRING, length: 32, options: [ 'default' => self::STATUS_PUBLISHED ] )]
    #[Assert\NotBlank]
    #[Assert\Choice( choices: self::STATUSES )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle( string $title ): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias( string $alias ): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent( string $content ): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate( \DateTimeInterface $publish_date ): self
    {
        $this->publish_date = $publish_date;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor( ?User $author ): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?PostCategory
    {
        return $this->category;
    }

    public function setCategory( ?PostCategory $category ): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCover(): ?PostCover
    {
        return $this->cover;
    }

    public function setCover(?PostCover $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus( string $status ): self
    {
        $this->status = $status;

        return $this;
    }
}
