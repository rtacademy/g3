<?php

declare( strict_types=1 );

spl_autoload_register(
    function( $class )
    {
        require_once( __DIR__ . '/' . str_replace( '\\', '/', $class ) . '.php' );
    }
);

// отримуємо сторінку з запиту
$page        = intval( preg_replace( '#[^0-9]#', '', $_GET['page'] ) ) ?? 2;        // за замовчуванням це друга сторінка

$postsModels = new \lib\models\PostsModel();
$posts       = $postsModels->getList( $page );

$posts_array = [];

if( !empty( $posts ) )
{
    foreach( $posts as $post )
    {
        $posts_array[] =
        [
            'id'           => $post->getId(),
            'title'        => $post->getTitle(),
            'alias'        => $post->getAlias(),
            'publish_date' => $post->getPublishDate( 'Y-m-d H:i:s' ),
            'description'  => $post->getDescription(),
            'url'          => $post->getUrl(),
            'category'     =>
            [
                'id'    => $post->getCategory()->getId(),
                'title' => $post->getCategory()->getTitle(),
                'alias' => $post->getCategory()->getAlias(),
            ],
            'cover'        => $post->getCover()->getListImgAttributes()
        ];
    }
}

// встановлюємо заголовок JSON
header( 'Content-Type: application/json' );

// перетворюємо масив в JSON
echo(
    json_encode( $posts_array )
);