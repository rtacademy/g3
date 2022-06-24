<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostCommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity( repositoryClass: PostCommentRepository::class )]
#[ApiResource(
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
    #[ORM\Column( type: 'integer' )]
    #[Groups( [ 'list', 'item' ] )]
    private $id;

    #[ORM\Column( type: 'datetime' )]
    #[Assert\NotBlank]
    #[Assert\Type( '\DateTime' )]
    #[Groups( [ 'list', 'item' ] )]
    private $created_date;

    #[ORM\Column( type: 'string', length: 255 )]
    #[Assert\NotBlank]
    #[Groups( [ 'list', 'item' ] )]
    private $comment;

    #[ORM\ManyToOne( targetEntity: User::class )]
    #[ORM\JoinColumn( nullable: false )]
    #[Groups( [ 'list', 'item' ] )]
    private $user;

    #[ORM\ManyToOne( targetEntity: Post::class )]
    #[ORM\JoinColumn( nullable: false )]
    #[Groups( [ 'list', 'item' ] )]
    private $post;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser( ?User $user ): self
    {
        $this->user = $user;

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
