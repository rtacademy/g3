<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity( repositoryClass: PostCategoryRepository::class )]
#[ORM\Table(name: '`post_category`')]
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
    order: [ 'id' => 'ASC' ],
    paginationEnabled: false,
)]
class PostCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column( type: Types::INTEGER )]
    #[Groups( [ 'list', 'item' ] )]
    private ?int $id = null;

    #[ORM\Column( type: Types::STRING, length: 64 )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 64
    )]
    #[Assert\Regex( '/^[A-Za-z0-9\'\.\-\s]+$/' )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $title = null;

    #[ORM\Column( type: Types::STRING, length: 64, unique: true )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 64
    )]
    #[Assert\Regex( '/^[a-z0-9\-]+$/' )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $alias = null;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

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
}
