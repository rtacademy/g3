<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity( repositoryClass: UserRepository::class )]
#[ORM\Table(name: '`user`')]
#[UniqueEntity( 'login' )]
#[UniqueEntity( 'email' )]
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
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column( type: Types::INTEGER )]
    #[Groups( [ 'list', 'item' ] )]
    private ?int $id = null;

    #[ORM\Column( type: Types::STRING, length: 32, unique: true )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 32
    )]
    #[Assert\Regex( '/^[a-z0-9\-]+$/' )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $login = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column( type: Types::STRING, length: 128 )]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column( type: Types::STRING, length: 255, unique: true )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 255
    )]
    #[Assert\Email]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $email = null;

    #[ORM\Column( type: Types::STRING, length: 64 )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 64
    )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $lastname = null;

    #[ORM\Column( type: Types::STRING, length: 64 )]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 64
    )]
    #[Groups( [ 'list', 'item' ] )]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin( string $login ): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword( string $password ): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail( string $email ): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname( string $lastname ): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname( string $firstname ): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
