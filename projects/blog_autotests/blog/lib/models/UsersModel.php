<?php

declare( strict_types=1 );

namespace lib\models;

class UsersModel
{
    /**
     * @param string $login
     * @param string $password
     *
     * @return \lib\entities\Author|null
     */
    public function getByLoginPassword( string $login, string $password ) : ?\lib\entities\Author
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметрами :login та :password
            $statement = $db->prepare(
                "
                    SELECT
                        u.id,
                        u.firstname,
                        u.lastname,
                        ur.name         AS role_name
                    FROM
                        users AS u
                    INNER JOIN
                        users_statuses AS us ON ( us.name = 'active' AND u.status_id = us.id )
                    INNER JOIN
                        users_roles AS ur ON ( u.role_id = ur.id )
                    WHERE
                        u.login = :login
                        AND
                        u.password = :password
                    LIMIT
                        1
                "
            );

            // виконання підготованого запита з параметрами :login та :password
            // пароль буде передано до БД вже у хешованому вигляді
            $statement->execute(
                [
                    ':login'    => $login,
                    ':password' => md5( $password ),
                ]
            );

            $row = $statement->fetch( \PDO::FETCH_ASSOC );

            if( empty( $row ) )
            {
                // нічого не знайдено
                return null;
            }

            // Author
            $author = new \lib\entities\Author();
            $author->setId( (int)$row['id'] );
            $author->setFirstName( $row['firstname'] );
            $author->setLastName( $row['lastname'] );
            $author->setRoleName( $row['role_name'] );

            return $author;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return null;
        }
    }
}