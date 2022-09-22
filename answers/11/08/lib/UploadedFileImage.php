<?php

declare( strict_types=1 );

namespace lib;

class UploadedFileImage extends UploadedFile
{
    protected int $minWidth  = 0;
    protected int $minHeight = 0;

    public function __construct(
        string $inputName, array $validMimetypes, int $maxFilesize,
        int    $minWidth = 0, int $minHeight = 0
    )
    {
        parent:: __construct( $inputName, $validMimetypes, $maxFilesize );

        $this->minWidth  = $minWidth;
        $this->minHeight = $minHeight;
    }

    /**
     * Перевірка на мінімальну ширину зображення
     *
     * @param \lib\Image $image
     *
     * @throws \lib\UploadedFileException
     * @return $this
     */
    public function checkImageMinWidth( \lib\Image $image ): self
    {
        if( $this->minWidth > $image->getWidth() )
        {
            throw new UploadedFileException( "Помилка #5. Мінімальна ширина зображення має бути не меншою за $this->minWidth px." );
        }

        return $this;
    }

    /**
     * Перевірка на мінімальну довжина зображення
     *
     * @param \lib\Image $image
     *
     * @throws \lib\UploadedFileException
     * @return $this
     */
    public function checkImageMinHeight( \lib\Image $image ): self
    {
        if( $this->minHeight > $image->getHeight() )
        {
            throw new UploadedFileException( "Помилка #6. Мінімальна довжина зображення має бути не меншою за $this->minHeight px." );
        }

        return $this;
    }
}