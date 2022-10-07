<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostCoverRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity( repositoryClass: PostCoverRepository::class )]
#[ORM\Table(name: '`post_cover`')]
#[UniqueEntity( 'filename' )]
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
class PostCover
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column( type: Types::INTEGER )]
    #[Groups( [ 'list', 'item' ] )]
    private ?int $id = null;

    #[ORM\Column( type: Types::STRING, length: 128, unique: true )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 4,
        max: 128
    )]
    #[Assert\Regex( '/^[a-z0-9\-\_\.]+$/' )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $filename = null;

    #[ORM\Column( type: Types::STRING, length: 64, nullable: true )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename( string $filename ): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle( ?string $title ): self
    {
        $this->title = $title;

        return $this;
    }
}
