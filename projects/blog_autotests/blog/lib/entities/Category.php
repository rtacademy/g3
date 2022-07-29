<?php

declare( strict_types=1 );

namespace lib\entities;

class Category
{
    protected int    $_id;
    protected string $_title;
    protected string $_alias;

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

    public function getTitle(): string
    {
        return $this->_title;
    }

    public function setTitle( string $title ): void
    {
        $this->_title = $title;
    }

    public function getAlias(): string
    {
        return $this->_alias;
    }

    public function setAlias( string $alias ): void
    {
        $this->_alias = $alias;
    }

    public function getUrl(): string
    {
        return './category.php?alias=' . $this->_alias;
    }
}