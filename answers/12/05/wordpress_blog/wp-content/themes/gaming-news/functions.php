<?php

// вмикаємо керування <title> через WordPress
add_theme_support('title-tag');

// вимикаємо відображення тега <meta name="generator" content="WordPress 5.8.1">
remove_action('wp_head', 'wp_generator');

// мініатюри (thumbnails)
add_theme_support( 'post-thumbnails' );                 // включаємо підтримку мініатюр
set_post_thumbnail_size( 350, 197, true );              // задаємо розмір мініатюр 350x197 з кадруванням
add_image_size( 'small-thumbnail', 310, 174, true );    // додаємо додаткові розміри мініатюрам, згідно дизайну - 310x174 + обрізати
add_image_size( 'middle-thumbnail', 550, 309, true );   // додаємо додаткові розміри мініатюрам, згідно дизайну - 550x309 + обрізати
add_image_size( 'big-thumbnail', 640, 360, true );      // додаємо додаткові розміри мініатюрам, згідно дизайну - 640x360 + обрізати
add_image_size( 'large-thumbnail', 1200, 675, true );   // додаємо додаткові розміри мініатюрам, згідно дизайну - 1200x675 + обрізати
// інші розміри мають бути вказані в Налаштуваннях Медіа:
// - Розмір мініатюр: 350x197 + обрізати
// - Середній розмір: 640х640
// - Великий розмір:  1200х1200

// реєструємо головне меню
register_nav_menus(
    [
        'main'      => 'Головне меню',
    ]
    // Масив із назвами (ключі масиву) та описами (значення ключів) кожного створюваного меню
);

// реєструємо sidebar #1
register_sidebar(
    [
        'name'          => 'Центральна частина у футері',       // Назва панелі віджетів. Назва буде видно у адмін-панелі WordPress.
        'id'            => 'footer-middle-sidebar',             //  Ідентифікатор віджету для виклику у шаблонах. Рядок, в якому не повинно бути великих букв і пробілів. Значення не повинно бути порожнім.
        'description'   => 'Центральна частина у футері',       // Текст, що описує, де буде виводитися панель віджетів. Відображається в панелі керування віджетами.
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h3>',                              // HTML код перед заголовком віджету
        'after_title'   => '</h3>',                             // HTML код після заголовка віджету
    ]
);

// реєструємо sidebar #2
register_sidebar(
    [
        'name'          => 'Права частина у футері',            // Назва панелі віджетів. Назва буде видно у адмін-панелі WordPress.
        'id'            => 'footer-right-sidebar',              //  Ідентифікатор віджету для виклику у шаблонах. Рядок, в якому не повинно бути великих букв і пробілів. Значення не повинно бути порожнім.
        'description'   => 'Права частина у футері',            // Текст, що описує, де буде виводитися панель віджетів. Відображається в панелі керування віджетами.
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h3>',                              // HTML код перед заголовком віджету
        'after_title'   => '</h3>',                             // HTML код після заголовка віджету
    ]
);

if( !function_exists( 'gaming_news_get_cover_list_tag' ) )
{
    /**
     * Функція відображення зображення для поста у списку, з підтримкою адаптивності
     */
    function gaming_news_get_cover_list_tag()
    {
        the_post_thumbnail(
            'small-thumbnail',
            [
                'srcset' =>
                    implode(
                        ',',
                        [
                            get_the_post_thumbnail_url( null, 'small-thumbnail' ) . ' 310w',
                            get_the_post_thumbnail_url( null,'thumbnail' ) . ' 350w',
                            get_the_post_thumbnail_url( null,'middle-thumbnail' ) . ' 550w',
                            get_the_post_thumbnail_url( null,'big-thumbnail' ) . ' 640w',
                        ]
                    ),
                'sizes' => '(max-width: 48rem) 550px, (max-width: 62rem) 350px, (max-width: 75rem) 310px, 550px',
                'alt'   => get_the_title(),
            ]
        );
    }
}

if( !function_exists('gaming_news_get_last_top_article') )
{
    /**
     * Функція відображення останньго ТОП запису (якщо такий є)
     */
    function gaming_news_get_last_top_article()
    {
        // отримуємо дані останнього запису
        $query = new WP_Query(
            [
                'category_name'  => 'top-article',  // (string) - Display posts that have "all" of these categories, using category slug.
                'posts_per_page' => 1,              // (int) - number of post to show per page.
                'post_type'      => 'post',         // (string / array) - use post types. Retrieves posts by Post Types, default value is 'post';
                'order'          => 'DESC',         // (string) - Designates the ascending or descending order of the 'orderby' parameter. Default to 'DESC'.
                'orderby'        => 'date',         // (string) - Sort retrieved posts by parameter. Defaults to 'date'. One or more options can be passed. EX: 'orderby' => 'menu_order title'
            ]
        );

        while( $query->have_posts() )
        {
            $query->the_post();

            ?>
            <article class="top-article">
                TOP ARTICLE <a href="<?= esc_url( get_permalink() ) ?>"><?= get_the_title() ?></a>
            </article>
            <?php
        }

        wp_reset_postdata();
    }
}