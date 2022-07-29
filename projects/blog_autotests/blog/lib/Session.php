<?php

declare( strict_types=1 );

namespace lib;

class Session
{
    public static function start() : void
    {
        session_set_cookie_params(
            [
                'lifetime'  => 3600,    // час життя кукі
                'secure'    => true,    // атрибут Secure
                'httponly'  => true     // атрибут HttpOnly
            ]
        );
        session_start();
    }

    public static function isAuthorized() : bool
    {
        return boolval( $_SESSION['isAuth'] ?? 0 );
    }

    public static function authorize( \lib\entities\Author $user ): void
    {
        $_SESSION['isAuth']    = 1;        // прапорець авторизації
        $_SESSION['id']        = $user->getId();
        $_SESSION['firstName'] = $user->getFirstName();
        $_SESSION['lastName']  = $user->getLastName();
        $_SESSION['role']      = $user->getRoleName();
    }

    public static function deauthorize(): void
    {
        // видаляємо всі змінні сесії
        $_SESSION = [];

        // видаляємо сесійну cookie
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '',
            time() - 86400,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );

        // знищуємо сесію
        session_destroy();
    }

    public static function getId() : int
    {
        return intval( $_SESSION['id'] ?? 0 );
    }

    public static function getFirstName() : string
    {
        return $_SESSION['firstName'] ?? '';
    }
}