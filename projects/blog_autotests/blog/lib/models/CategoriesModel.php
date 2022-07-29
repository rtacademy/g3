<?php

declare( strict_types=1 );

namespace lib\models;

class CategoriesModel
{
    /**
     * @return \lib\entities\Category[]
     */
    public function getList() : array
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // виконуємо запит
            $statement = $db->query(
                '
                    SELECT
                        `id`,
                        `title`,
                        `alias`
                    FROM
                        posts_categories
                    ORDER BY
                        `title` ASC
                ',
                \PDO::FETCH_ASSOC
            );

            $items = [];

            foreach( $statement as $row )
            {
                // Category
                $item = new \lib\entities\Category();
                $item->setId( (int)$row['id'] );
                $item->setTitle( $row['title'] );
                $item->setAlias( $row['alias'] );

                $items[] = $item;
            }

            return $items;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return [];
        }
    }

    /**
     * @param string $alias
     *
     * @return bool
     */
    public function existsByAlias( string $alias ) : bool
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметром :alias
            $statement = $db->prepare(
                "
                    SELECT
                        count(id) AS c
                    FROM
                        posts_categories
                    WHERE
                        alias = :alias
                "
            );

            // виконання підготованого запита з параметром :alias
            $statement->execute(
                [
                    ':alias' => $alias,
                ]
            );

            return boolval( $statement->fetch( \PDO::FETCH_ASSOC )['c'] ?? 0 );
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return true;
        }
    }

    /**
     * @param string $alias
     * @param int    $id
     *
     * @return bool
     */
    public function existsByAliasExceptID( string $alias, int $id ) : bool
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметрами :alias та :id
            $statement = $db->prepare(
                "
                    SELECT
                        count(id) AS c
                    FROM
                        posts_categories
                    WHERE
                        alias = :alias
                        AND
                        id <> :id                         
                "
            );

            // виконання підготованого запита з параметрами :alias та :id
            $statement->execute(
                [
                    ':alias' => $alias,
                    ':id' => $id,
                ]
            );

            return boolval( $statement->fetch( \PDO::FETCH_ASSOC )['c'] ?? 0 );
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return true;
        }
    }

    /**
     * @param string $title
     * @param string $alias
     *
     * @return bool
     */
    public function add( string $title, string $alias ) : bool
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметрами :title та :alias
            $statement = $db->prepare(
                "
                    INSERT INTO 
                        posts_categories
                        (title, alias)
                    VALUES
                        (:title, :alias)
                "
            );

            // виконання підготованого запита з параметрами :title та :alias
            $statement->execute(
                [
                    ':title' => $title,
                    ':alias' => $alias,
                ]
            );

            return true;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return false;
        }
    }

    /**
     * @param int    $id
     * @param string $title
     * @param string $alias
     *
     * @return bool
     */
    public function edit( int $id, string $title, string $alias ) : bool
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметрами :id, :title та :alias
            $statement = $db->prepare(
                "
                    UPDATE 
                        posts_categories
                    SET
                        title = :title,
                        alias = :alias
                    WHERE
                        id = :id
                "
            );

            // виконання підготованого запита з параметрами :id, :title та :alias
            $statement->execute(
                [
                    ':id'    => $id,
                    ':title' => $title,
                    ':alias' => $alias,
                ]
            );

            return true;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return false;
        }
    }

    /**
     * @param int $id
     *
     * @return \lib\entities\Category|null
     */
    public function getSingle( int $id ) : ?\lib\entities\Category
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметром :id
            $statement = $db->prepare(
                "
                    SELECT
                        pc.id,
                        pc.title,
                        pc.alias
                    FROM
                        posts_categories AS pc
                    WHERE
                        pc.id = :id
                    LIMIT
                        1
                "
            );

            // виконання підготованого запита з параметром :id
            $statement->execute(
                [
                    ':id' => $id,
                ]
            );

            $row = $statement->fetch( \PDO::FETCH_ASSOC );

            if( empty( $row ) )
            {
                // нічого не знайдено
                return null;
            }

            // Category
            $category = new \lib\entities\Category();
            $category->setId( (int)$row['id'] );
            $category->setTitle( $row['title'] );
            $category->setAlias( $row['alias'] );

            return $category;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return null;
        }
    }
}