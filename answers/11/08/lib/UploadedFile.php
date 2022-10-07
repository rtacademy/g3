<?php

declare( strict_types=1 );

namespace lib;

class UploadedFile
{
    protected array $file           = [];
    protected array $validMimetypes = [];
    protected int   $maxFilesize    = 0;

    /**
     * @param string $inputName
     * @param array  $validMimetypes
     * @param int    $maxFilesize
     */
    public function __construct( string $inputName, array $validMimetypes, int $maxFilesize )
    {
        $this->file           = $_FILES[ $inputName ] ?? [];
        $this->validMimetypes = $validMimetypes;
        $this->maxFilesize    = $maxFilesize;
    }

    /**
     * Перевірка на відправку файлу
     *
     * @throws \lib\UploadedFileException
     * @return $this
     */
    public function checkNotEmpty(): self
    {
        if( empty( $this->file ) )
        {
            throw new UploadedFileException( 'Помилка #1. Необхідно завантажити файл.' );
        }

        return $this;
    }

    /**
     * Перевірка на успішне завантаження файлу на сервер
     *
     * @throws \lib\UploadedFileException
     * @return $this
     */
    public function checkUploadStatus(): self
    {
        if( ( $this->file['error'] ?? 500 ) !== UPLOAD_ERR_OK )
        {
            throw new UploadedFileException( 'Помилка #2. Сталася помилка під час завантаження файлу.' );
        }

        return $this;
    }

    /**
     * Перевірка на формат файлу, що має бути один з наведених
     *
     * @throws \lib\UploadedFileException
     * @return $this
     */
    public function checkMimetype(): self
    {
        if( !in_array( ( $this->file['type'] ?? '' ), $this->validMimetypes, true ) )
        {
            throw new UploadedFileException(
                'Помилка #3. Формат файлу має бути один з наведених: ' . implode( ', ', $this->validMimetypes )
            );
        }

        return $this;
    }

    /**
     * Перевірка на максимальний розмір файлу
     *
     * @throws \lib\UploadedFileException
     * @return $this
     */
    public function checkMaxFilesize(): self
    {
        if( ( $this->file['size'] ?? 0 ) > $this->maxFilesize )
        {
            throw new UploadedFileException( "Помилка #4. Файл повинен бути менше $this->maxFilesize байт." );
        }

        return $this;
    }
}