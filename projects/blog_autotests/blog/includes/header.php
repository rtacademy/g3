<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php

        $siteName = 'Gaming News';

        if( !empty( $post ) )
        {
            echo( $post->getTitle() . ' | ' );
        }

        echo( $siteName );

        ?>
        </title>
    <link rel="stylesheet" href="./css/styles.css" media="all">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Extra+Condensed&display=swap" rel="stylesheet">
    <script async src="./js/index.js"></script>
</head>
<body>

<header>
    <a href="./index.php" class="logo"><h1><?= $siteName ?></h1></a>
    <nav>
        <ul>
            <?php

            if( !empty( $websiteMenuItems ) )
            {
                foreach( $websiteMenuItems as $item )
                {
                    echo( '<li><a href="' . $item->getHref() . '">' . $item->getTitle() . '</a></li>' );
                }
            }

            ?>
        </ul>
    </nav>
</header>