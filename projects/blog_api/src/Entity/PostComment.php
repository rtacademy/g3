<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostCommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity( repositoryClass: PostCommentRepository::class )]
#[ORM\Table(name: '`post_comment`')]
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
    order: [ 'id' => 'ASC' ],
    paginationEnabled: false,
)]
class PostComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column( type: Types::INTEGER )]
    #[Groups( [ 'list', 'item' ] )]
    private ?int $id = null;

    #[ORM\Column( type: Types::DATETIME_MUTABLE )]
    #[Assert\NotBlank]
    #[Assert\Type( \DateTime::class )]
    #[Groups( [ 'list', 'item' ] )]
    private ?\DateTimeInterface $created_date = null;

    #[ORM\Column( type: Types::STRING, length: 255 )]
    #[Assert\NotBlank]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $comment = null;

    #[ORM\ManyToOne( targetEntity: User::class )]
    #[ORM\JoinColumn( nullable: false )]
    #[Groups( [ 'list', 'item' ] )]
    private ?User $author = null;

    #[ORM\ManyToOne( targetEntity: Post::class )]
    #[ORM\JoinColumn( nullable: false )]
    #[Groups( [ 'list', 'item' ] )]
    private ?Post $post = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate( \DateTimeInterface $created_date ): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment( string $comment ): self
    {
        $this->comment = $comment;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost( ?Post $post ): self
    {
        $this->post = $post;

        return $this;
    }
}
