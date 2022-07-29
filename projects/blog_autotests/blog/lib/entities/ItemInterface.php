<?php

declare( strict_types=1 );

namespace lib\entities;

interface ItemInterface
{
    public function getId(): int;

    public function setId( int $id );

    public function getTitle(): string;

    public function setTitle( string $title );

    public function getAlias(): string;

    public function setAlias( string $alias );

    public function getDescription(): string;

    public function setDescription( string $description );

    public function getAuthor(): Author;

    public function setAuthor( Author $author );

    public function getPublishDate( string $format ): string;

    public function setPublishDate( string $publishDate );

    public function getCategory(): Category;

    public function setCategory( Category $category );

    public function getCover(): PostCover;

    public function setCover( PostCover $cover );

    public function getUrl(): string;
}