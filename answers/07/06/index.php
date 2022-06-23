<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Web->App->DB</title>
</head>
<body>
<?php

    try
    {
        // MariaDB
        $host       = 'rtacademy_database_mariadb';
        $port       = 3306;
        $dbname     = 'helloworld';
        $dbuser     = 'helloworld';
        $dbpassword = 'helloworld';

        $db = new \PDO(
            "mysql:host=$host;port=$port;dbname=$dbname",
            $dbuser,
            $dbpassword
        );

        $db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

        $statement = $db->query(
            'SELECT now()'
        );
        
        $datetime_mariadb = $statement->fetchColumn();
        
        // PostgreSQL
        $host       = 'rtacademy_database_postgresql';
        $port       = 5432;
        $dbname     = 'helloworld';
        $dbuser     = 'helloworld';
        $dbpassword = 'helloworld';

        $db = new \PDO(
            "pgsql:host=$host;port=$port;dbname=$dbname",
            $dbuser,
            $dbpassword
        );

        $db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

        $db->query('SET TIME ZONE "Europe/Kiev"');
        
        $statement = $db->query(
            'SELECT now()'
        );
        
        $datetime_postgresql = $statement->fetchColumn();

        // Result
        echo(
            'Час PHP: ' . date( 'c' ) .
            '<br>'.
            'Час з БД PostgreSQL: ' . $datetime_postgresql .
            '<br>'.
            'Час з БД MariaDB: ' . $datetime_mariadb
        );
    }
    catch( \PDOException $e )
    {
        echo( 'Помилка БД: ' . $e->getMessage() );
    }

?>
</body>
</html>
