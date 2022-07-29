<?php

declare( strict_types=1 );

namespace lib\entities;

class PostStatus
{
    protected int    $_id;
    protected string $_name;
    protected string $_title;

    public function getId(): int
    {
        return $this->_id;
    }

    public function setId( int $id ): void
    {
        $this->_id = $id;
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function setName( string $name ): void
    {
        $this->_name = $name;
    }

    public function getTitle(): string
    {
        return $this->_title;
    }

    public function setTitle( string $title ): void
    {
        $this->_title = $title;
    }
}