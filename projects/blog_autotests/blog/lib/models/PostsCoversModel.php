<?php

declare( strict_types=1 );

namespace lib\models;

class PostsCoversModel
{
    protected const COVER_SIZES =
    [
        [
            'width'  => 1200,
            'height' => 675,
        ],
        [
            'width'  => 640,
            'height' => 360,
        ],
        [
            'width'  => 550,
            'height' => 309,
        ],
        [
            'width'  => 350,
            'height' => 197,
        ],
        [
            'width'  => 310,
            'height' => 174,
        ],
    ];

    /**
     * @param string $filePath
     *
     * @return string
     */
    protected function _getFilename( string $filePath ) : string
    {
        return md5_file( $filePath );
    }

    /**
     * @param array  $cover
     * @param string $filename
     *
     * @return bool
     */
    protected function _resizeCover( array $cover, string $filename ) : bool
    {
        // читаємо вміст зображення
        $fileContents = file_get_contents( $cover['tmp_name'] );

        // створення екземпляру класу GdImage з зображення, автоматично визначаючи його тип
        $imageSource = imagecreatefromstring( $fileContents );

        $imageMaxWidth   = imagesx( $imageSource );           // визначаємо ширину зображення в пікселях
        $imageMaxHeight  = imagesy( $imageSource );           // визначаємо висоту зображення в пікселях

        // нарізаємо thumbnails для кожного розміру, враховуючи масив self::COVER_SIZES
        foreach( self::COVER_SIZES as $size )
        {
            if( ( $imageMaxWidth / $imageMaxHeight ) >= 1 )
            {
                $imageWidth  = (int)( $imageMaxHeight * ( $size['width'] / $size['height'] ) );
                $imageHeight = $imageMaxHeight;
            }
            else
            {
                $imageWidth  = $imageMaxWidth;
                $imageHeight = (int)( $imageMaxWidth / ( $size['width'] / $size['height'] ) );
            }

            // вирізаємо прямокутник від лівого верхнього кута зображення
            $imageResult = imagecrop(
                $imageSource,
                [
                    'x'      => 0,
                    'y'      => 0,
                    'width'  => $imageWidth,
                    'height' => $imageHeight
                ]
            );

            // зменшимо зображення до X по ширині; висота буде задана автоматично з пропорцій
            $imageResult = imagescale( $imageResult, $size['width'] );

            if( empty( $imageResult ) )
            {
                return false;
            }

            $thumbnailPath = './images/' . $filename. '_' . $size['width'] . '.jpg';
            imagejpeg( $imageResult, $thumbnailPath );         // зберігаємо з новим ім'ям у форматі JPEG
            imagedestroy( $imageResult );                      // звільняємо пам'ять, зайняту зменшеним зображенням
        }

        // звільняємо пам'ять, зайняту зображенням
        imagedestroy( $imageSource );

        return true;
    }

    /**
     * @param array  $cover
     * @param string $title
     *
     * @return \lib\entities\PostCover|null
     */
    public function add( array $cover, string $title ) : ?\lib\entities\PostCover
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // отримуємо назву файла, відносно його вмісту, що обчислено за алгоритмом MD5
            $filename = $this->_getFilename( $cover['tmp_name'] );

            // нарізаємо необхідні зображення з префіксом $filename
            if( ! $this->_resizeCover( $cover, $filename ) )
            {
                return null;
            }

            // TODO: необхідно перевірити що такого filename ще немає у БД, бо filename UNIQUE

            // готуємо підготований запит з параметрами та поверненням ID доданого запису
            $statement = $db->prepare(
                "
                    INSERT INTO 
                        posts_covers
                        (filename, alt)
                    VALUES
                        (:filename, :alt)
                    RETURNING
                        id
                "
            );

            // виконання підготованого запита з параметрами
            $statement->execute(
                [
                    ':filename' => $filename,
                    ':alt'      => $title,
                ]
            );

            $id = (int)( $statement->fetch( \PDO::FETCH_ASSOC )['id'] ?? 0 );

            // PostCover
            $postCover = new \lib\entities\PostCover();
            $postCover->setId( $id );

            return $postCover;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return null;
        }
    }
}