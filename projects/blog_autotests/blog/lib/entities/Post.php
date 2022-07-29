<?php

declare( strict_types=1 );

namespace lib\entities;

class Post implements ItemInterface
{
    protected int        $_id;
    protected string     $_title;
    protected string     $_alias;
    protected string     $_description;
    protected string     $_content;
    protected Author     $_author;
    protected int        $_publishDate;
    protected Category   $_category;
    protected PostCover  $_cover;
    protected PostStatus $_status;

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

    public function getDescription(): string
    {
        return $this->_description;
    }

    public function setDescription( string $description ): void
    {
        $this->_description = $description;
    }

    public function getContent(): string
    {
        return $this->_content;
    }

    public function setContent( string $content ): void
    {
        $this->_content = $content;
    }

    public function getAuthor(): Author
    {
        return $this->_author;
    }

    public function setAuthor( Author $author ): void
    {
        $this->_author = $author;
    }

    public function getPublishDate( string $format = 'd.m.Y H:i' ): string
    {
        return date( $format, $this->_publishDate );
    }

    public function setPublishDate( string $publishDate ): void
    {
        $this->_publishDate = strtotime( $publishDate );
    }

    public function getCategory(): Category
    {
        return $this->_category;
    }

    public function setCategory( Category $category ): void
    {
        $this->_category = $category;
    }

    public function getCover(): PostCover
    {
        return $this->_cover;
    }

    public function setCover( PostCover $cover ): void
    {
        $this->_cover = $cover;
    }

    public function getStatus(): PostStatus
    {
        return $this->_status;
    }

    public function setStatus( PostStatus $status ): void
    {
        $this->_status = $status;
    }

    public function getUrl(): string
    {
        return './single.php?id=' . $this->_id;
    }
}