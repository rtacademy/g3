<?php

declare( strict_types=1 );

namespace lib;

class ImageGd extends Image
{
    /**
     * @param string $tempName
     *
     * @throws \lib\ImageException
     */
    public function __construct( string $tempName )
    {
        parent::__construct( $tempName );

        $this->sourceImage = imagecreatefromstring( file_get_contents( $tempName ) );

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
        return imagesx( $this->sourceImage );
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return imagesy( $this->sourceImage );
    }

    /**
     * @throws \lib\ImageException
     * @return $this
     */
    public function cropImage(): self
    {
        $this->resultImage = imagecrop(
            $this->sourceImage,
            $this->getCropBox()
        );

        if( empty( $this->resultImage ) )
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
        // зменшимо зображення до 300px по висоті; ширина буде задана автоматично з пропорцій
        $this->resultImage = imagescale( $this->resultImage, $newImageWidth, $newImageHeight );

        if( empty( $this->resultImage ) )
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

        if( !imagejpeg( $this->resultImage, $newImageFilename ) )
        {
            throw new ImageException( "Помилка #11. Виникла помилка при збереженні нового зображення $newImageFilename" );
        }

        chmod( $newImageFilename, 0644 );

        return $this;
    }
}
