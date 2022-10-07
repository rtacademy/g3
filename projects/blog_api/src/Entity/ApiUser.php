<?php

namespace App\Entity;

use App\Repository\ApiUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity( repositoryClass: ApiUserRepository::class )]
#[ORM\Table(name: '`api_user`')]
#[UniqueEntity( 'token' )]
class ApiUser implements UserInterface
{
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

    private array $roles = [];

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
}