<?php

declare( strict_types=1 );

namespace lib;

class ImageSuccessResult
{
    public function __construct(
        protected string $fileName,
        protected int    $width = -1,
        protected int    $height = -1
    )
    {

    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }
}