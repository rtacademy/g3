<?php

declare( strict_types=1 );

spl_autoload_register(
    function( $class )
    {
        require_once( __DIR__ . '/' . str_replace( '\\', '/', $class ) . '.php' );
    }
);

// запуск сесії з параметрами
\lib\Session::start();

// обробка деавторизації
$authController    = new \lib\controllers\AuthController();
$authController->logout();
