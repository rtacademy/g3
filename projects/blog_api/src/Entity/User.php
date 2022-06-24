<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('login')]
#[UniqueEntity('email')]
#[ApiResource(
    collectionOperations:
    [
        'get' =>
        [
            'normalization_context' => [ 'groups' => 'user:list' ],
        ],
        'post' =>
        [
            'normalization_context' => [ 'groups' => 'user:list' ],
        ],
    ],
    itemOperations:
    [
        'get' =>
        [
            'normalization_context' => [ 'groups' => 'user:item' ],
        ],
        'put' =>
        [
            'normalization_context' => [ 'groups' => 'user:item' ],
        ],
        'delete' =>
        [
            'normalization_context' => [ 'groups' => 'user:item' ],
        ],
    ],
    order: [ 'id' => 'ASC' ],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:list', 'user:item'])]
    private $id;

    #[ORM\Column(type: 'string', length: 32, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-z0-9\-]/')]
    #[Groups(['user:list', 'user:item'])]
    private $login;

    #[ORM\Column(type: 'string', length: 128)]
    #[Assert\NotBlank]
    private $password;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 255
    )]
    #[Assert\Email]
    #[Groups(['user:list', 'user:item'])]
    private $email;

    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 64
    )]
    #[Groups(['user:list', 'user:item'])]
    private $lastname;

    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 64
    )]
    #[Groups(['user:list', 'user:item'])]
    private $firstname;

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

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }
}
