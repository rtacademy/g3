<?php

declare( strict_types=1 );

namespace lib;

class Kernel
{
    protected const INPUT_NAME            = 'file';
    protected const INPUT_VALID_MIMETYPES = [ 'image/jpeg', 'image/png', 'image/gif' ];
    protected const INPUT_MAX_FILESIZE    = 10485760;
    protected const INPUT_MIN_WIDTH       = 500;
    protected const INPUT_MIN_HEIGHT      = 500;
    protected const RESULT_IMAGE_WIDTH    = -1;
    protected const RESULT_IMAGE_HEIGHT   = 300;

    protected static string              $errorMessage  = '';
    protected static ?ImageSuccessResult $successResult = null;

    /**
     * @return void
     */
    public static function initialize(): void
    {
        try
        {
            if( empty( $_POST ) )
            {
                return;
            }

            self::createDataDirectory();

            $image = new \lib\ImageImagemagick( $_FILES[ self::INPUT_NAME ]['tmp_name'] ?? '' );

            $uploadedFile = new \lib\UploadedFileImage(
                self::INPUT_NAME, self::INPUT_VALID_MIMETYPES, self::INPUT_MAX_FILESIZE,
                self::INPUT_MIN_WIDTH, self::INPUT_MIN_HEIGHT
            );

            $uploadedFile
                ->checkNotEmpty()
                ->checkUploadStatus()
                ->checkMimetype()
                ->checkMaxFilesize()
                ->checkImageMinWidth( $image )
                ->checkImageMinHeight( $image );

            $image
                ->cropImage()
                ->resizeImage( self::RESULT_IMAGE_WIDTH, self::RESULT_IMAGE_HEIGHT )
                ->saveImage( './data/' . uniqid( '', true ) . '.jpg' );

            self::$successResult = new ImageSuccessResult(
                $image->getResultFileName(),
                self::RESULT_IMAGE_WIDTH,
                self::RESULT_IMAGE_HEIGHT
            );
        }
        catch( \Throwable $exception )
        {
            self::$errorMessage = $exception->getMessage();
        }
    }

    /**
     * @return void
     */
    protected static function createDataDirectory(): void
    {
        $directory = './data';
        if( file_exists( $directory ) )
        {
            return;
        }
        if( !mkdir( $directory, 0777 ) && !is_dir( $directory ) )
        {
            throw new \RuntimeException( "Помилка #20. Директорію $directory не було створено" );
        }
    }

    /**
     * @return string
     */
    public static function getErrorMessage(): string
    {
        return self::$errorMessage;
    }

    /**
     * @return \lib\ImageSuccessResult|null
     */
    public static function getSuccessResult(): ?ImageSuccessResult
    {
        return self::$successResult;
    }
}