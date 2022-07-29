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

// отримуємо ID запису
$postId = intval( preg_replace( '#[^0-9]#', '', $_GET['id'] ?? '0' ) );
$post   = null;

if( !empty( $postId ) )
{
    $postsModels = new \lib\models\PostsModel();
    $post        = $postsModels->getSingle( $postId );

    if( empty( $post ) )      // header 404
    {
        header( 'HTTP/1.1 404 Not Found' );
    }
}

// головне меню
$websiteMenuModel  = new \lib\models\WebsiteMenuModel();
$websiteMenuItems  = $websiteMenuModel->getList();

require_once( './includes/header.php' );

?>
<main class="main-post">

<?php

if( !empty( $post ) )
{

    ?>
    <article class="post">
        <div class="post-image">
            <?= $post->getCover()->getImgTag( $post->getCover()->getSingleImgAttributes() ) ?>

            <div class="post-header">
                <h1><?= $post->getTitle() ?></h1>

                <div class="post-info">
                    <div class="author">
                        <div>Author:</div>
                        <a href="<?= $post->getAuthor()->getUrl() ?>"><?= $post->getAuthor()->getFirstName() . ' ' . $post->getAuthor()->getLastName() ?></a>
                    </div>

                    <div class="date">
                        <div>PUBLISHED ON:</div>
                        <time datetime="<?= $post->getPublishDate('c') ?>"><?= $post->getPublishDate() ?></time>
                    </div>

                    <div class="category">
                        <div>Category:</div>
                        <a href="<?= $post->getCategory()->getUrl() ?>"><?= $post->getCategory()->getTitle() ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="post-content">
            <?= $post->getContent() ?>
        </div>
    </article>
    <?php

}
else            // 404
{
    ?>
    <div class="e404">
        <h2>404</h2>
        <h3>Not Found</h3>
    </div>
    <?php
}

?>
</main>
<?php

require_once( './includes/footer.php' );
