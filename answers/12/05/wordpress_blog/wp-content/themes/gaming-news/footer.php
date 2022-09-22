<footer>

    <div class="inner">

        <div class="main">
            <h3><a href="/"><?= get_bloginfo( 'name' ) ?></a></h3>

            <ul class="social">
                <li><a href="#"><img src="<?= get_template_directory_uri() ?>/images/icons/001-facebook.svg" alt="facebook"></a></li>
                <li><a href="#"><img src="<?= get_template_directory_uri() ?>/images/icons/013-twitter.svg" alt="twitter"></a></li>
                <li><a href="#"><img src="<?= get_template_directory_uri() ?>/images/icons/008-youtube.svg" alt="youtube"></a></li>
            </ul>

            <p>&copy; Copyright, <?= date('Y') ?></p>
        </div>

        <div class="nav">
            <?php

                if( is_active_sidebar( 'footer-middle-sidebar' ) )
                {
                    dynamic_sidebar( 'footer-middle-sidebar' );     // виводимо сайдбар, ім'я визначене у functions.php
                }

            ?>
        </div>

        <div class="nav">
            <?php

            if( is_active_sidebar( 'footer-right-sidebar' ) )
            {
                dynamic_sidebar( 'footer-right-sidebar' );          // виводимо сайдбар, ім'я визначене у functions.php
            }

            ?>
        </div>
    </div>

</footer>

<?php wp_footer() ?>

</body>
</html>