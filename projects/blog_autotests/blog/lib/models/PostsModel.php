<?php

declare( strict_types=1 );

namespace lib\models;

class PostsModel
{
    /** @var int Кількість записів на одній сторінці */
    public const COUNT_PER_PAGE = 3;

    /**
     * @param int $page
     *
     * @return \lib\entities\Post[]
     */
    public function getList( int $page = 1 ) : array
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // OFFSET розраховується за такою формулою, відносно номера сторінки
            $offset = ( $page - 1 ) * self::COUNT_PER_PAGE;

            // виконуємо запит
            $statement = $db->query(
                '
                    SELECT
                        p.id,
                        p.title,
                        p.alias,
                        p.description,
                        p.publish_date,
                        p.category_id,
                        pc.title        AS category_title,
                        pc.alias        AS category_alias,
                        p.author_id,
                        u.firstname     AS author_firstname,
                        u.lastname      AS author_lastname,
                        pc2.filename    AS cover_filename,
                        pc2.alt         AS cover_alt
                    FROM
                        posts AS p
                    LEFT JOIN
                        posts_categories AS pc ON ( p.category_id = pc.id )
                    LEFT JOIN
                        users AS u ON ( p.author_id = u.id )
                    LEFT JOIN
                        posts_covers AS pc2 ON ( p.cover_id = pc2.id )
                    INNER JOIN
                        posts_statuses AS ps2 ON ( ps2.name = \'active\' AND p.status_id = ps2.id )
                    WHERE
                        p.status_id = ps2.id
                        AND
                        p.publish_date <= now()
                    ORDER BY
                        p.publish_date DESC
                    LIMIT
                        ' . self::COUNT_PER_PAGE . '
                    OFFSET
                        ' . $offset,
                \PDO::FETCH_ASSOC
            );

            $posts = [];

            foreach( $statement as $row )
            {
                // Author
                $author = new \lib\entities\Author();
                $author->setId( (int)$row['author_id'] );
                $author->setFirstName( $row['author_firstname'] );
                $author->setLastName( $row['author_lastname'] );

                // Category
                $category = new \lib\entities\Category();
                $category->setId( (int)$row['category_id'] );
                $category->setTitle( $row['category_title'] );
                $category->setAlias( $row['category_alias'] );

                // PostCover
                $cover = new \lib\entities\PostCover();
                $cover->setFilename( $row['cover_filename'] );
                $cover->setAlt( $row['cover_alt'] );

                // Post
                $post = new \lib\entities\Post();
                $post->setId( (int)$row['id'] );
                $post->setTitle( $row['title'] );
                $post->setAlias( $row['alias'] );
                $post->setDescription( $row['description'] );
                $post->setAuthor( $author );
                $post->setPublishDate( $row['publish_date'] );
                $post->setCategory( $category );
                $post->setCover( $cover );

                $posts[] = $post;
            }

            return $posts;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return [];
        }
    }

    /**
     * @return int
     */
    public function getTotalCount() : int
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // виконуємо запит
            $statement = $db->query(
                "
                    SELECT
                        count(p.id) as c
                    FROM
                        posts AS p
                    INNER JOIN
                        posts_statuses AS ps2 ON ( ps2.name = 'active' AND p.status_id = ps2.id )
                    WHERE
                        p.status_id = ps2.id
                        AND
                        p.publish_date <= now()
                ",
                \PDO::FETCH_ASSOC
            );

            return (int) $statement->fetch( \PDO::FETCH_ASSOC )['c'] ?? 0;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return 0;
        }
    }

    /**
     * @param int $id
     *
     * @return \lib\entities\Post|null
     */
    public function getSingle( int $id ) : ?\lib\entities\Post
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметром :post_id
            $statement = $db->prepare(
                "
                    SELECT
                        p.id,
                        p.title,
                        p.alias,
                        p.description,
                        p.publish_date,
                        p.category_id,
                        p.content,
                        pc.title        AS category_title,
                        pc.alias        AS category_alias,
                        p.author_id,
                        u.firstname     AS author_firstname,
                        u.lastname      AS author_lastname,
                        pc2.filename    AS cover_filename,
                        pc2.alt         AS cover_alt
                    FROM
                        posts AS p
                    LEFT JOIN
                        posts_categories AS pc ON ( p.category_id = pc.id )
                    LEFT JOIN
                        users AS u ON ( p.author_id = u.id )
                    LEFT JOIN
                        posts_covers AS pc2 ON ( p.cover_id = pc2.id )
                    INNER JOIN
                        posts_statuses AS ps2 ON ( ps2.name = 'active' AND p.status_id = ps2.id )
                    WHERE
                        p.status_id = ps2.id
                        AND
                        p.publish_date <= now()
                        AND
                        p.id = :post_id
                    LIMIT
                        1
                "
            );

            // виконання підготованого запита з параметром :post_id
            $statement->execute(
                [
                    ':post_id' => $id,
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
            $author->setId( (int)$row['author_id'] );
            $author->setFirstName( $row['author_firstname'] );
            $author->setLastName( $row['author_lastname'] );

            // Category
            $category = new \lib\entities\Category();
            $category->setId( (int)$row['category_id'] );
            $category->setTitle( $row['category_title'] );
            $category->setAlias( $row['category_alias'] );

            // PostCover
            $cover = new \lib\entities\PostCover();
            $cover->setFilename( $row['cover_filename'] );
            $cover->setAlt( $row['cover_alt'] );

            // Post
            $post = new \lib\entities\Post();
            $post->setId( (int)$row['id'] );
            $post->setTitle( $row['title'] );
            $post->setAlias( $row['alias'] );
            $post->setDescription( $row['description'] );
            $post->setContent( $row['content'] );
            $post->setAuthor( $author );
            $post->setPublishDate( $row['publish_date'] );
            $post->setCategory( $category );
            $post->setCover( $cover );

            return $post;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );

            return null;
        }
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $content
     * @param string $publish_date
     * @param int    $categoryId
     * @param int    $coverId
     * @param int    $statusId
     *
     * @return \lib\entities\Post|null
     */
    public function add( string $title, string $description, string $content, string $publish_date, int $categoryId, int $coverId, int $statusId ) : ?\lib\entities\Post
    {
        try
        {
            // підʼєднуємось до БД
            $db = \lib\DbConnection::getConnection();

            // готуємо підготований запит з параметрами та поверненням ID доданого запису
            $statement = $db->prepare(
                "
                INSERT INTO 
                posts
                (
                    title, 
                    alias, 
                    description, 
                    content, 
                    publish_date, 
                    author_id, 
                    category_id, 
                    cover_id, 
                    status_id
                )
                VALUES
                (
                    :title, 
                    :alias, 
                    :description, 
                    :content, 
                    :publish_date, 
                    :author_id, 
                    :category_id, 
                    :cover_id, 
                    :status_id
                )
                RETURNING
                    id
                "
            );

            // TODO: необхідна обробка коректності ID автора
            // TODO: необхідна обробка коректності ID категорії
            // TODO: необхідна обробка коректності ID стану

            // виконання підготованого запита з параметрами
            $statement->execute(
                [
                    ':title'        => $title,
                    ':alias'        => $this->_createAlias( $title ),
                    ':description'  => $description,
                    ':content'      => $content,
                    ':publish_date' => date( 'Y-m-d H:i:s', strtotime( $publish_date ) ),
                    ':author_id'    => \lib\Session::getId(),
                    ':category_id'  => $categoryId,
                    ':cover_id'     => $coverId,
                    ':status_id'    => $statusId,
                ]
            );

            $id = (int)( $statement->fetch( \PDO::FETCH_ASSOC )['id'] ?? 0 );

            // Post
            $post = new \lib\entities\Post();
            $post->setId( $id );

            return $post;
        }
        catch( \PDOException $e )
        {
            echo( '<div style="padding:1rem;background:#a00;color:#fff;">Помилка БД: ' . $e->getMessage() . '</div>' );
            return null;
        }
    }

    /**
     * @param string $title
     *
     * @return string
     */
    protected function _createAlias( string $title ) : string
    {
        return
            preg_replace(
                '/\-{2,}/',
                '-',
                preg_replace(
                    '/[^0-9a-z-]/',
                    '-',
                    strtolower( $title )
                )
            );
    }
}