<?php

declare( strict_types=1 );

namespace lib;

interface ImageInterface
{
    /**
     * @return int
     */
    public function getWidth(): int;

    /**
     * @return int
     */
    public function getHeight(): int;

    /**
     * @return $this
     */
    public function cropImage(): self;

    /**
     * @param int $newImageWidth
     * @param int $newImageHeight
     *
     * @return $this
     */
    public function resizeImage( int $newImageWidth = -1, int $newImageHeight = -1 ): self;

    /**
     * @param string $newImageFilename
     *
     * @return $this
     */
    public function saveImage( string $newImageFilename ): self;
}