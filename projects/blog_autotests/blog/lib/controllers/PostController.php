<?php

declare( strict_types=1 );

namespace lib\controllers;

class PostController
{
    protected const TITLE_MIN_LENGTH       = 2;
    protected const TITLE_MAX_LENGTH       = 128;
    protected const TITLE_REGEXP           = '/^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,\':;<>\s]+$/iu';
    protected const DESCRIPTION_MIN_LENGTH = 2;
    protected const DESCRIPTION_MAX_LENGTH = 255;
    protected const DESCRIPTION_REGEXP     = '/^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,\':;\s]+$/ium';
    protected const CONTENT_MIN_LENGTH     = 2;
    protected const CONTENT_MAX_LENGTH     = 65536;
    protected const CONTENT_REGEXP         = '/^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,\':;<>\s]+$/ium';
    protected const PUBLISH_DATE_REGEXP    = '/^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])T(0[0-9]|1[0-9]|2[0-3])\:[0-5][0-9]$/';
    protected const COVER_MAX_FILE_SIZE    = 10485760;
    protected const COVER_IMAGE_MIN_WIDTH  = 1200;
    protected const COVER_IMAGE_MIN_HEIGHT = 675;

    protected string $_error_message   = '';
    protected string $_success_message = '';

    protected function _validateTitle( string $title ) : bool
    {
        if( strlen( $title ) < self::TITLE_MIN_LENGTH )
        {
            $this->_error_message = 'Необхідно заповнити поле "Назва"';
            return false;
        }

        if( strlen( $title ) > self::TITLE_MAX_LENGTH )
        {
            $this->_error_message = 'Максимальна довжина поля "Назва" - ' . self::TITLE_MAX_LENGTH . ' символів';
            return false;
        }

        if( !preg_match( self::TITLE_REGEXP, $title ) )
        {
            $this->_error_message = 'Поле "Назва" містить некоректні символи';
            return false;
        }

        return true;
    }

    protected function _validateDescription( string $description ) : bool
    {
        if( strlen( $description ) < self::DESCRIPTION_MIN_LENGTH )
        {
            $this->_error_message = 'Необхідно заповнити поле "Опис"';
            return false;
        }

        if( strlen( $description ) > self::DESCRIPTION_MAX_LENGTH )
        {
            $this->_error_message = 'Максимальна довжина поля "Опис" - ' . self::DESCRIPTION_MAX_LENGTH . ' символів';
            return false;
        }

        if( !preg_match( self::DESCRIPTION_REGEXP, $description ) )
        {
            $this->_error_message = 'Поле "Опис" містить некоректні символи';
            return false;
        }

        return true;
    }

    protected function _validateContent( string $content ) : bool
    {
        if( strlen( $content ) < self::CONTENT_MIN_LENGTH )
        {
            $this->_error_message = 'Необхідно заповнити поле "Вміст"';
            return false;
        }

        if( strlen( $content ) > self::CONTENT_MAX_LENGTH )
        {
            $this->_error_message = 'Максимальна довжина поля "Вміст" - ' . self::CONTENT_MAX_LENGTH . ' символів';
            return false;
        }

        if( !preg_match( self::CONTENT_REGEXP, $content ) )
        {
            $this->_error_message = 'Поле "Вміст" містить некоректні символи';
            return false;
        }

        return true;
    }

    protected function _validatePublishDate( string $publish_date ) : bool
    {
        if( !preg_match( self::PUBLISH_DATE_REGEXP, $publish_date ) )
        {
            $this->_error_message = 'Некоректно введено дату та час в поле "Дата публікації"';
            return false;
        }

        return true;
    }

    protected function _validateCategory( string $category ): bool
    {
        if( empty( $category ) )
        {
            $this->_error_message = 'Необхідно заповнити поле "Категорія"';
            return false;
        }

        return true;
    }

    protected function _validateCover( array $cover ): bool
    {
        if( empty( $cover ) )
        {
            $this->_error_message = 'Необхідно заповнити поле "Зображення"';
            return false;
        }

        // перевірка успішне завантаження файлу на сервер
        if( $cover['error'] !== UPLOAD_ERR_OK )
        {
            $this->_error_message = 'Сталася помилка під час завантаження зображення.';
            return false;
        }

        // перевірка на формат файлу
        if( !in_array( $cover['type'], [ 'image/jpeg' ] ) )
        {
            $this->_error_message = 'Зображення повинно мати формат JPEG.';
            return false;
        }

        // перевірка на розмір файлу
        if( $cover['size'] > self::COVER_MAX_FILE_SIZE )
        {
            $this->_error_message = 'Зображення має бути менше ' . self::COVER_MAX_FILE_SIZE . ' байт.';
            return false;
        }

        // читаємо вміст зображення
        $file_contents = file_get_contents( $cover['tmp_name'] );

        // створення екземпляру класу GdImage з зображення, автоматично визначаючи його тип
        $image_source = imagecreatefromstring( $file_contents );

        $image_width  = imagesx( $image_source );           // визначаємо ширину зображення в пікселях
        $image_height = imagesy( $image_source );           // визначаємо висоту зображення в пікселях

        // звільняємо пам'ять, зайняту зображенням
        imagedestroy( $image_source );

        // перевірка мінімальної ширини та висоти
        if( $image_width < self::COVER_IMAGE_MIN_WIDTH || $image_height < self::COVER_IMAGE_MIN_HEIGHT )
        {
            $this->_error_message = 'Розмір зображення має бути більшим за ' . self::COVER_IMAGE_MIN_WIDTH . 'x' . self::COVER_IMAGE_MIN_HEIGHT;
            return false;
        }

        return true;
    }

    protected function _validateStatus( string $status ): bool
    {
        if( empty( $status ) )
        {
            $this->_error_message = 'Необхідно заповнити поле "Стан"';
            return false;
        }

        return true;
    }

    public function getErrorMessage() : string
    {
        return $this->_error_message;
    }

    public function getSuccessMessage() : string
    {
        return $this->_success_message;
    }

    public function add(): void
    {
        // у випадку неавторизованого користувача - переходимо на першу сторінку
        if( ! \lib\Session::isAuthorized() )
        {
            header( 'Location: ./index.php' );
            return;
        }

        // не виконуємо весь код нижче, якщо форма не заповнена
        if( empty( $_POST ) )
        {
            return;
        }

        // отримання даних
        $title        = $_POST['title'] ?? '';
        $description  = $_POST['description'] ?? '';
        $content      = $_POST['content'] ?? '';
        $publish_date = $_POST['publish_date'] ?? '';
        $category     = $_POST['category'] ?? '';
        $cover        = $_FILES['cover'] ?? [];
        $status       = $_POST['status'] ?? '';

        // перевірка/валідація отриманих даних
        if( !$this->_validateTitle( $title ) ||
            !$this->_validateDescription( $description ) ||
            !$this->_validateContent( $content ) ||
            !$this->_validatePublishDate( $publish_date ) ||
            !$this->_validateCategory( $category ) ||
            !$this->_validateCover( $cover ) ||
            !$this->_validateStatus( $status ) )
        {
            return;
        }

        // TODO: додати транзакцію

        // створюємо екземпляр моделі PostsCoversModel
        $postsCoversModel = new \lib\models\PostsCoversModel();
        $postCover        = $postsCoversModel->add( $cover, $title );

        if( empty( $postCover ) )
        {
            $this->_error_message = 'Сталася помилка при додаванні зображення у БД';
            return;
        }

        // створюємо екземпляр моделі PostsModel
        $postsModel = new \lib\models\PostsModel();

        // додаємо новий пост/запис
        $post       = $postsModel->add(
            $title, $description, $content, $publish_date, intval( $category ), $postCover->getId(), intval( $status )
        );

        if( empty( $post ) )
        {
            $this->_error_message = 'Сталася помилка при додаванні запису у БД';
            return;
        }

        // переходимо на новий запис
        header( 'Location: ' . $post->getUrl() );
    }

    public function edit( int $id ): ?\lib\entities\Post
    {
        // у випадку неавторизованого користувача - переходимо на першу сторінку
        if( ! \lib\Session::isAuthorized() )
        {
            header( 'Location: ./index.php' );
            return null;
        }

        // створюємо екземпляр моделі PostsModel
        $postsModel = new \lib\models\PostsModel();

        // отримуємо категорію з БД за ID
        $post = $postsModel->getSingle( $id );       // TODO: додати метод отримання запису без врахування стану та дати публікації

        if( empty( $post ) )
        {
            header( 'HTTP/1.1 404 Not Found' );
            $this->_error_message = 'Запису з таким ID не існує';
            return null;
        }

        // TODO: перевірка на автора/адміна

        // не виконуємо весь код нижче, якщо форма не заповнена
        if( !empty( $_POST ) )
        {
            // TODO
        }

        return $post;
    }
}