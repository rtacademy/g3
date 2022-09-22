<?php get_header(); ?>

<main class="main-posts">
    <?php

    if( is_home() )
    {
        gaming_news_get_last_top_article();
    }
    elseif( is_category() )
    {
        ?>
        <h2><?php single_cat_title() ?></h2>
        <?php
    }

    ?>
    <div class="posts">
        <?php

        if( have_posts() )
        {
            while( have_posts() )
            {
                // завантажуємо інформацію про поточний елемент у змінні
                the_post();

                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <a href="<?= esc_url( get_permalink() ) ?>" class="cover"><?php gaming_news_get_cover_list_tag() ?></a>
                    <a href="<?= esc_url( get_permalink() ) ?>" class="title"><h2><?php the_title() ?></h2></a>
                    <time datetime="<?= get_the_date('c') ?>"><?php the_date() ?></time>
                    <a href="<?= esc_url( get_permalink() ) ?>" class="description"><?php the_excerpt() ?></a>
                    <a href="<?= esc_url( get_permalink() ) ?>" class="more">Continue Reading</a>
                </article>
                <?php
            }
        }
        else
        {
            // коли відсутні записи/пости
            ?>
            <div class="no-articles">No articles</div>
            <?php
        }

        ?>
    </div>
    <?php

    // пагінація/pagination
    the_posts_pagination(
        [
            'show_all'           => false, // показані всі сторінки, що беруть участь у пагінації
            'end_size'           => 1,     // кількість сторінок на кінцях
            'mid_size'           => 1,     // кількість сторінок навколо поточної
            'prev_next'          => true,  // чи виводити бічні посилання "попередня/наступна сторінка".
            'prev_text'          => __( '« Previous' ),
            'next_text'          => __( 'Next »' ),
        ]
    );

    ?>
</main>
<?php

get_footer();
