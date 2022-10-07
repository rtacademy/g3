<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= get_stylesheet_uri() ?>" media="all">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Extra+Condensed&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body>

<header>
    <a href="/" class="logo"><h1><?= get_bloginfo( 'name' ) ?></h1></a>
    <nav>
        <?php

        wp_nav_menu(
            [
                'theme_location'  => 'main',
            ]
        );

        ?>
    </nav>
</header>