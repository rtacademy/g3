<footer>

    <div class="inner">

        <div class="main">
            <h3><a href="./index.php"><?= $siteName ?></a></h3>

            <ul class="social">
                <li><a href="#"><img src="./images/icons/001-facebook.svg" alt="facebook"></a></li>
                <li><a href="#"><img src="./images/icons/013-twitter.svg" alt="twitter"></a></li>
                <li><a href="#"><img src="./images/icons/008-youtube.svg" alt="youtube"></a></li>
            </ul>

            <p>&copy; Copyright, <?= date('Y') ?></p>
        </div>

        <div class="nav">
            <h3>User Area</h3>

            <ul>
                <?php

                if( \lib\Session::isAuthorized() )
                {

                ?>
                <li>Hello, <?= \lib\Session::getFirstName() ?></li>
                <li><a href="./post_add.php" id="user-area-add-post">Add Post</a></li>
                <li><a href="./category_add.php" id="user-area-add-category">Add Category</a></li>
                <li><a href="./logout.php" id="user-area-logout">Logout</a></li>
                <?php

                }
                else
                {

                ?>
                <li><a href="./login.php" id="user-area-login">Login</a></li>
                <?php

                }
                ?>
            </ul>
        </div>

        <div class="nav">
            <h3>Main Navigation</h3>

            <ul>
                <li><a href="./index.php">Home</a></li>
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
        </div>

    </div>

</footer>

</body>
</html>