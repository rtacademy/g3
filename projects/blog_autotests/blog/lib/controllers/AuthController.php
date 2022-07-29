<?php

declare( strict_types=1 );

namespace lib\controllers;

class AuthController
{
    protected const LOGIN_MIN_LENGTH = 2;
    protected const LOGIN_MAX_LENGTH = 32;
    protected const LOGIN_REGEXP     = '/^[a-z0-9\.\-\_]+$/i';

    protected const PASSWORD_MIN_LENGTH = 8;
    protected const PASSWORD_MAX_LENGTH = 32;
    protected const PASSWORD_REGEXP     = '/^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,\':;<>\s]+$/iu';

    protected string $_error_message = '';

    protected function _validateLogin( string $login ) : bool
    {
        if( strlen( $login ) < self::LOGIN_MIN_LENGTH )
        {
            $this->_error_message = 'Необхідно заповнити поле "Логін"';
            return false;
        }

        if( strlen( $login ) > self::LOGIN_MAX_LENGTH )
        {
            $this->_error_message = 'Максимальна довжина поля "Логін" - ' . self::LOGIN_MAX_LENGTH . ' символів';
            return false;
        }

        if( !preg_match( self::LOGIN_REGEXP, $login ) )
        {
            $this->_error_message = 'Поле "Логін" містить некоректні символи';
            return false;
        }

        return true;
    }

    protected function _validatePassword( string $password ) : bool
    {
        if( strlen( $password ) < self::PASSWORD_MIN_LENGTH )
        {
            $this->_error_message = 'Необхідно заповнити поле "Пароль"';
            return false;
        }

        if( strlen( $password ) > self::PASSWORD_MAX_LENGTH )
        {
            $this->_error_message = 'Максимальна довжина поля "Пароль" - ' . self::PASSWORD_MAX_LENGTH . ' символів';
            return false;
        }

        if( !preg_match( self::PASSWORD_REGEXP, $password ) )
        {
            $this->_error_message = 'Поле "Пароль" містить некоректні символи';
            return false;
        }

        return true;
    }

    public function getErrorMessage() : string
    {
        return $this->_error_message;
    }

    public function login(): void
    {
        // у випадку авторизованого користувача - переходимо на першу сторінку
        if( \lib\Session::isAuthorized() )
        {
            header( 'Location: ./index.php' );
            return;
        }

        // не виконуємо весь код нижче, якщо форма не заповнена
        if( empty( $_POST ) )
        {
            return;
        }

        $login    = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        if( !$this->_validateLogin( $login ) )
        {
            return;
        }

        if( !$this->_validatePassword( $password ) )
        {
            return;
        }

        // шукаємо активного користувача у БД з введенним логіном та паролем
        $usersModel = new \lib\models\UsersModel();
        $user       = $usersModel->getByLoginPassword( $login, $password );

        if( empty( $user ) )
        {
            $this->_error_message = 'Користувача з введеними даними не існує';
            return;
        }

        // авторизуємо користувача
        \lib\Session::authorize( $user );

        // переходимо на першу сторінку
        header( 'Location: ./index.php' );
    }

    public function logout(): void
    {
        // деавторизація користувача
        \lib\Session::deauthorize();

        // переходимо на першу сторінку
        header( 'Location: ./index.php' );
    }
}