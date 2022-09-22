<?php

declare( strict_types=1 );

namespace lib;

class ImageImagemagick extends Image
{
    /**
     * @param string $tempName
     *
     * @throws \lib\ImageException
     */
    public function __construct( string $tempName )
    {
        parent::__construct( $tempName );

        $this->sourceImage = new \Imagick( $tempName );

        if( empty( $this->sourceImage ) )
        {
            throw new ImageException( 'Помилка #8. Неможливо обробити вхідне зображення' );
        }
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->sourceImage->getImageWidth();
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->sourceImage->getImageHeight();
    }

    /**
     * @throws \lib\ImageException
     * @return $this
     */
    public function cropImage(): self
    {
        $this->resultImage = clone $this->sourceImage;
        $cropBox           = $this->getCropBox();

        if( !$this->resultImage->cropImage( $cropBox['width'], $cropBox['height'], $cropBox['x'], $cropBox['y'], ) )
        {
            throw new ImageException( 'Помилка #9. Виникла помилка при вирізанні частини зображення' );
        }

        return $this;
    }

    /**
     * @param int $newImageWidth
     * @param int $newImageHeight
     *
     * @throws \lib\ImageException
     * @return $this
     */
    public function resizeImage( int $newImageWidth = -1, int $newImageHeight = -1 ): self
    {
        if( !$this->resultImage->resizeImage( $newImageWidth, $newImageHeight, \Imagick::FILTER_LANCZOS, 1 ) )
        {
            throw new ImageException( 'Помилка #10. Виникла помилка при зменшенні частини зображення' );
        }

        return $this;
    }

    /**
     * @param string $newImageFilename
     *
     * @throws \lib\ImageException
     * @return $this
     */
    public function saveImage( string $newImageFilename ): self
    {
        $this->resultFileName = $newImageFilename;

        $this->resultImage->setImageFormat( 'JPEG' );
        $this->resultImage->setImageCompressionQuality( 95 );

        if( !$this->resultImage->writeImage( $newImageFilename ) )
        {
            throw new ImageException( "Помилка #11. Виникла помилка при збереженні нового зображення $newImageFilename" );
        }

        chmod( $newImageFilename, 0644 );

        return $this;
    }
}
