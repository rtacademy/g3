<?php

declare( strict_types=1 );

namespace lib\entities;

abstract class User
{
    protected int    $_id;
    protected string $_firstName;
    protected string $_lastName;
    protected string $_roleName;

    public function __construct()
    {

    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function setId( int $id ): void
    {
        $this->_id = $id;
    }

    public function getFirstName(): string
    {
        return $this->_firstName;
    }

    public function setFirstName( string $firstName ): void
    {
        $this->_firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->_lastName;
    }

    public function setLastName( string $lastName ): void
    {
        $this->_lastName = $lastName;
    }

    public function getRoleName(): string
    {
        return $this->_roleName;
    }

    public function setRoleName( string $roleName ): void
    {
        $this->_roleName = $roleName;
    }
}