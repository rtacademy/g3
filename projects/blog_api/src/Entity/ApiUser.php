<?php

namespace App\Entity;

use App\Repository\ApiUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity( repositoryClass: ApiUserRepository::class )]
#[ORM\Table(name: '`api_user`')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity( 'token' )]
class ApiUser implements UserInterface
{
    public const STATUS_ENABLED  = 'enabled';
    public const STATUS_DISABLED = 'disabled';
    public const STATUSES        = [ self::STATUS_ENABLED, self::STATUS_DISABLED ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column( type: Types::INTEGER )]
    private ?int $id = null;

    #[ORM\Column( type: Types::STRING, length: 128, unique: true )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 128,
        max: 128
    )]
    #[Assert\Regex( '/^[A-Za-z0-9]+$/' )]
    private ?string $token = null;

    private array $roles = [];

    #[ORM\Column( type: Types::DATETIME_MUTABLE )]
    #[Assert\Type( \DateTime::class )]
    #[Groups( [ 'list', 'item' ] )]
    private ?\DateTimeInterface $created_date = null;

    #[ORM\Column( type: Types::STRING, length: 32, options: [ 'default' => self::STATUS_ENABLED ] )]
    #[Assert\NotBlank]
    #[Assert\Choice( choices: self::STATUSES )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken( string $token ): self
    {
        $this->token = $token;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if( empty( $roles ) )
        {
            $roles[] = 'ROLE_API';
        }

        return array_unique( $roles );
    }

    public function setRoles( $roles ): void
    {
        $this->roles = $roles;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials(): void
    {

    }

    public function getUserIdentifier(): string
    {
        return 'token';
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

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

    #[ORM\PrePersist]
    public function onPrePersist() : void
    {
        $this->created_date = new \DateTime('now');
    }
}