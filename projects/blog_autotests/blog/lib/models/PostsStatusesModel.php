<?php

declare( strict_types=1 );

namespace lib\models;

class PostsStatusesModel
{
    /**
     * @return \lib\entities\PostStatus[]
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
                        `name`,
                        `title`
                    FROM
                        posts_statuses
                    ORDER BY
                        `name` ASC
                ',
                \PDO::FETCH_ASSOC
            );

            $items = [];

            foreach( $statement as $row )
            {
                // PostStatus
                $item = new \lib\entities\PostStatus();
                $item->setId( (int)$row['id'] );
                $item->setName( $row['name'] );
                $item->setTitle( $row['title'] );

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
}