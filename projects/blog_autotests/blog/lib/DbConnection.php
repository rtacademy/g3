<?php

declare( strict_types=1 );

namespace lib;

class DbConnection
{
    private const HOST       = 'rtacademy_g2_database';
    private const PORT       = 3306;
    private const DBNAME     = 'blog_rtacademy_g2';
    private const DBUSER     = 'rtacademy_g2';
    private const DBPASSWORD = 'rtacademy_g2';

    private static ?\PDO $_db = null;

    /**
     * Метод підʼєднується до БД за допомогою PDO тільки у випадку якщо ще не було виконано підʼєднання
     *
     * @throws \PDOException
     * @return \PDO
     */
    public static function getConnection(): \PDO
    {
        if( empty( self::$_db ) )
        {
            // підʼєднуємось до БД
            self::$_db = new \PDO(
                'mysql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DBNAME,
                self::DBUSER,
                self::DBPASSWORD,
                [
                    // встановлюємо режим викиду PDOException при виникненні помилки
                    \PDO::ATTR_ERRMODE              => \PDO::ERRMODE_EXCEPTION,
                    // встановлюємо UTF-8
                    \PDO::MYSQL_ATTR_INIT_COMMAND   => 'SET NAMES utf8',
                ]
            );
        }

        return self::$_db;
    }
}
