<?php

declare( strict_types=1 );

spl_autoload_register( static fn( $class ) => require str_replace( '\\', '/', "$class.php" ) );

\lib\Kernel::initialize();

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>#11.07 (GD)</title>
    <style>
        body {
            font: normal 1rem/1.5 Verdana,sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
        }
        main {
            margin: 20vh auto 2rem auto;
            text-align: center;
        }
        main div.error {
            background: #df6b6b;
            padding: .8rem;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1rem;
            color:#fff;
        }
        main form {
            padding-bottom: 2rem;
        }
        main form label {
            display: block;
            font-size:.8rem;
            color: #333;
            margin-bottom: .3rem;
        }
        main form input {
            padding-right: 1rem;
        }
        main form input,
        main form button {
            font-size: 1rem;
            line-height: 1.5;
        }
        main div.result div {
            margin-bottom: .3rem;
        }
    </style>
</head>
<body>
    <main>
        <form enctype="multipart/form-data" method="POST">
            <?php

            $errorMessage = \lib\Kernel::getErrorMessage();

            if( !empty( $errorMessage ) )
            {
                echo '<div class="error">' . $errorMessage . '</div>';
            }

            ?>
            <label for="file">
                Оберіть зображення до 10МБ в форматах JPG, GIF або PNG.
            </label>
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
            <input type="file" name="file" id="file" accept="image/png,image/gif,image/jpeg">
            <button type="submit">Надіслати</button>
        </form>

        <?php

        /** @var \lib\ImageSuccessResult $successResult */
        $successResult = \lib\Kernel::getSuccessResult();

        if( $successResult !== null )
        {
            echo
                '<div class="result">' .
                '    <div>Оброблене завантажене зображення</div>' .
                '    <div><img src="' . $successResult->getFileName() . '" height="' . $successResult->getHeight() . '"></div>' .
                '</div>';
        }

        ?>
    </main>
</body>
</html>