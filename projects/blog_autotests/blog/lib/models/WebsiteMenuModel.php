<?php

declare( strict_types=1 );

namespace lib\models;

class WebsiteMenuModel
{
    /**
     * @return \lib\entities\WebsiteMenu[]
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
                        `href`
                    FROM
                        website_menu
                    ORDER BY
                        `order` ASC
                ',
                \PDO::FETCH_ASSOC
            );

            $items = [];

            foreach( $statement as $row )
            {
                // WebsiteMenu
                $item = new \lib\entities\WebsiteMenu();
                $item->setId( (int)$row['id'] );
                $item->setTitle( $row['title'] );
                $item->setHref( $row['href'] );

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