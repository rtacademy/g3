<?php

declare( strict_types=1 );

namespace lib;

abstract class Image implements ImageInterface
{
    protected object|bool $sourceImage;
    protected object|bool $resultImage;

    protected string $tempName       = '';
    protected string $resultFileName = '';

    /**
     * @param string $tempName
     *
     * @throws \lib\ImageException
     */
    public function __construct( string $tempName )
    {
        $this->tempName = $tempName;

        if( empty( $tempName ) || !is_readable( $tempName ) )
        {
            throw new ImageException( 'Помилка #7. Тимчасовний файл відсутній' );
        }
    }

    /**
     * @return string
     */
    public function getResultFileName(): string
    {
        return $this->resultFileName;
    }

    /**
     * @return array
     */
    protected function getCropBox(): array
    {
        $image_width  = $this->getWidth();
        $image_height = $this->getHeight();

        // обчислюємо розміри для пропорції 4:5
        if( $image_width < $image_height )
        {
            $side_4x = $image_width;
            $side_5x = (int)( 5 * $image_width / 4 );
        }
        else        // $image_width >= $image_height
        {
            $side_4x = (int)( 4 * $image_height / 5 );
            $side_5x = $image_height;
        }

        return
        [
            'x'      => (int)( $image_width / 2 - $side_4x / 2 ),
            'y'      => (int)( $image_height / 2 - $side_5x / 2 ),
            'width'  => $side_4x,
            'height' => $side_5x,
        ];
    }
}
