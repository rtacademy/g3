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

// обробка форми редагування запису
$postController    = new \lib\controllers\PostController();
$post = $postController->edit( $postId );

// категорії записів
$categoriesModel  = new \lib\models\CategoriesModel();
$categoriesItems  = $categoriesModel->getList();

// стани записів
$postsStatusesModel  = new \lib\models\PostsStatusesModel();
$postsStatuses       = $postsStatusesModel->getList();

// головне меню
$websiteMenuModel  = new \lib\models\WebsiteMenuModel();
$websiteMenuItems  = $websiteMenuModel->getList();

require_once( './includes/header.php' );

?>
<main class="main-form">
    <h1>Редагування запису <?= $post?->getTitle() ?? '' ?></h1>

    <?php

    // обробка існування помилки при заповненні форми та її відображення
    $errorMessage = $postController->getErrorMessage();

    if( !empty( $errorMessage ) )
    {
        echo( '<div class="error-general-text">' . $errorMessage . '</div>' );
    }

    // обробка існування тексту з успішним повідомленням
    $successMessage = $postController->getSuccessMessage();

    if( !empty( $successMessage ) )
    {
        echo( '<div class="success-general-text">' . $successMessage . '</div>' );
    }

    if( !empty( $post ) )
    {

    ?>
    <form method="post" name="post" action="" class="form" id="form-post" enctype="multipart/form-data">
        <ul>
            <li><label for="form-post-title">Назва<span>*</span></label></li>
            <li>
                <input type="text" minlength="2" maxlength="128" value="<?= $post->getTitle() ?? '' ?>" name="title" id="form-post-title" required placeholder="Введіть назву запису">
            </li>

            <li><label for="form-post-description">Опис<span>*</span></label></li>
            <li><textarea name="description" id="form-post-description" required placeholder="Введіть опис"><?= $post->getDescription() ?? '' ?></textarea></li>

            <li><label for="form-post-content">Вміст<span>*</span></label></li>
            <li><textarea name="content" id="form-post-content" required placeholder="Введіть вміст"><?= $post->getContent() ?? '' ?></textarea></li>

            <li><label for="form-post-publish-date">Дата публікації<span>*</span></label></li>
            <li><input type="datetime-local" value="<?= $post->getPublishDate('Y-m-d\TH:i') ?? '' ?>" name="publish_date" id="form-post-publish-date" required></li>

            <li><label for="form-post-category">Категорія<span>*</span></label></li>
            <li>
                <select name="category" id="form-post-category" required>
                    <option value="" disabled selected>[Оберіть категорію]</option>
                    <?php

                    if( !empty( $categoriesItems ) )
                    {
                        $currentCategory = intval( $post->getCategory()->getId() ?? 0 );

                        foreach( $categoriesItems as $category )
                        {
                            echo( '<option value="' . $category->getId() . '" '.( $currentCategory === $category->getId() ? ' selected' : '' ).'>' . $category->getTitle() . '</option>' );
                        }
                    }

                    ?>
                </select>
            </li>

            <li><label for="form-post-cover">Зображення<span>*</span></label></li>
            <li>
                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                <input type="file" name="cover" id="form-post-cover" accept="image/jpeg" required>
            </li>

            <li><label for="form-post-status">Стан<span>*</span></label></li>
            <li>
                <select name="status" id="form-post-status" required>
                    <option value="" disabled selected>[Оберіть стан]</option>
                    <?php

                    if( !empty( $postsStatuses ) )
                    {
                        $currentStatus = intval( $post->getStatus()?->getId() ?? 0 );

                        foreach( $postsStatuses as $status )
                        {
                            echo( '<option value="' . $status->getId() . '" '.( $currentStatus === $status->getId() ? ' selected' : '' ).'>' . $status->getTitle() . '</option>' );
                        }
                    }

                    ?>
                </select>
            </li>

            <li><button type="submit" name="submit" id="form-post-submit">Надіслати</button></li>
        </ul>
    </form>
    <?php

    }

    ?>
</main>

<script>
const validate =
{
    'initialize' : () =>
    {
        // видалення блоків з текстом помилок
        document.querySelectorAll('#form-post div.error-text').forEach( ( element ) => element.remove() );

        // видалення класів valid та invalid
        document.querySelectorAll('#form-post .valid, #form-post .invalid').forEach(
            ( element ) =>
            {
                element.classList.remove( 'invalid' );
                element.classList.remove( 'valid' );
            }
        );
    },

    '_setElementInvalid' : ( element ) =>
    {
        // видалення CSS-класа з коректними даними
        element.classList.remove('valid');

        // додавання CSS-класа з некоректними даними
        element.classList.add('invalid');
    },

    '_setElementValid' : ( element ) =>
    {
        // видалення CSS-класа з некоректними даними
        element.classList.remove('invalid');

        // додавання CSS-класа з коректними даними
        element.classList.add('valid');
    },

    '_createErrorElement' : ( text ) =>
    {
        const errorDiv = document.createElement('div');
        errorDiv.setAttribute( 'class', 'error-text' );
        errorDiv.append( document.createTextNode( text ) );

        return errorDiv;
    },

    '_showHideErrors' : ( element, errorText ) =>
    {
        if( errorText.length > 0 )          // якщо текст помилки присутній
        {
            // створення та додавання елемента з текстом помилки
            const errorDiv = validate._createErrorElement( errorText );
            element.parentElement.append( errorDiv );

            // додавання CSS-класа з некоректними даними
            validate._setElementInvalid( element );

            return false;                   // містить помилки
        }

        // додавання CSS-класа з коректними даними
        validate._setElementValid( element );

        return true;                        // все гаразд
    },

    'title' : ( value, element ) =>
    {
        const
            minLength   = 2,
            maxLength   = 128,
            regExp      = /^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,':;<>\s]+$/iu;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Назва"';
        }
        else if( value.length > maxLength )
        {
            errorText = 'Максимальна довжина поля "Назва" - ' + maxLength + ' символів';
        }
        else if( ! regExp.test( value ) )
        {
            errorText = 'Поле "Назва" містить некоректні символи';
        }

        return validate._showHideErrors( element, errorText );
    },

    'description' : ( value, element ) =>
    {
        const
            minLength   = 2,
            maxLength   = 255,
            regExp      = /^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,':;\s]+$/ium;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Опис"';
        }
        else if( value.length > maxLength )
        {
            errorText = 'Максимальна довжина поля "Опис" - ' + maxLength + ' символів';
        }
        else if( ! regExp.test( value ) )
        {
            errorText = 'Поле "Опис" містить некоректні символи';
        }

        return validate._showHideErrors( element, errorText );
    },

    'content' : ( value, element ) =>
    {
        const
            minLength   = 2,
            maxLength   = 65536,
            regExp      = /^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,':;<>\s]+$/ium;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Вміст"';
        }
        else if( value.length > maxLength )
        {
            errorText = 'Максимальна довжина поля "Вміст" - ' + maxLength + ' символів';
        }
        else if( ! regExp.test( value ) )
        {
            errorText = 'Поле "Вміст" містить некоректні символи';
        }

        return validate._showHideErrors( element, errorText );
    },

    'publish_date' : ( value, element ) =>
    {
        const
            minLength   = 16,
            maxLength   = 16,
            // дата та час за форматом "YYYY-MM-DDTHH:MM"
            regExp = /^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])T(0[0-9]|1[0-9]|2[0-3])\:[0-5][0-9]$/;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Дата публікації"';
        }
        else if( value.length > maxLength )
        {
            errorText = 'Максимальна довжина поля "Дата публікації" - ' + maxLength + ' символів';
        }
        else if( ! regExp.test( value ) )
        {
            errorText = 'Некоректно введено дату та час в поле "Дата публікації"';
        }

        return validate._showHideErrors( element, errorText );
    },

    'category' : ( value, element ) =>
    {
        const
            minLength       = 1;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Категорія"';
        }

        return validate._showHideErrors( element, errorText );
    },

    'cover' : ( value, element ) =>
    {
        const
            minLength       = 1;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Зображення"';
        }

        return validate._showHideErrors( element, errorText );
    },

    'status' : ( value, element ) =>
    {
        const
            minLength       = 1;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Стан"';
        }

        return validate._showHideErrors( element, errorText );
    },
};

const formGetElementsAndValues = () =>
{
    // отримання елементів
    const element = {};
    element.title           = document.getElementById( 'form-post-title' );
    element.description     = document.getElementById( 'form-post-description' );
    element.content         = document.getElementById( 'form-post-content' );
    element.publish_date    = document.getElementById( 'form-post-publish-date' );
    element.category        = document.getElementById( 'form-post-category' );
    element.cover           = document.getElementById( 'form-post-cover' );

    // отримання значень
    const value = {};
    value.title             = ( element.title.value || '' ).toString();
    value.description       = ( element.description.value || '' ).toString();
    value.content           = ( element.content.value || '' ).toString();
    value.publish_date      = ( element.publish_date.value || '' ).toString();
    value.category          = ( element.category.value || '' ).toString();
    value.cover             = ( element.cover.value || '' ).toString();

    return { element, value };
};

const formSubmit = () =>
{
    // отримання елементів та їх значень
    const { element, value }        = formGetElementsAndValues();
    // цей спосіб називається "Деструктуризація"
    // https://learn.javascript.ru/destructuring-assignment#destrukturizatsiya-obekta

    // видаляємо всі елементи з помилками, червоні та зелені рамки
    validate.initialize();

    let hasError = false;                   // за замовчуванням - помилки відсутні

    for( let item in value )                // синтаксис for...in для перебирання обʼєктів: https://developer.mozilla.org/ru/docs/Web/JavaScript/Reference/Statements/for...in
    {
        // item: це ключ об`єкта, наприклад "title", "content", "publish_date" і т.д.

        if( ! validate[ item ]( value[ item ], element[ item ] ) )          // виклик методів обʼєкта validate за іменем ключа та параметрами також за іменем ключа
        {
            hasError = true;                                                // якщо validate[item]() поверне false -- hasError отримає true
        }
    }

    return !hasError;       // true - продовжити надсилання форми, false - скасувати
};

const formInputFilePreview = function( event )          // Увага! Тут function, бо я використовую this, в якому міститься поточний елемент
{
    // видаляємо всі попередні <img>
    this.parentElement.querySelectorAll( 'img' ).forEach( element => element.remove() );

    const [ file ] = this.files;                // зберігаємо беремо перший елемент з масиву this.files у змінну file

    if( file )
    {
        // створюємо img-елемент з preview обраного зображення
        const img = document.createElement( 'img' );
        img.setAttribute( 'class', 'preview' );
        img.setAttribute( 'src', URL.createObjectURL( file ) );

        this.parentElement.append( img );
    }
};

// обробка submit форми (натиснення Enter спричине Submit форми, бо присутній input type=submit)
document.getElementById('form-post').onsubmit = formSubmit;

// обробка натискання на кнопку "Надіслати"
document.getElementById('form-post-submit').onclick = formSubmit;

// preview зображення, при виборі
document.getElementById('form-post-cover').onchange = formInputFilePreview;
</script>
<?php

require_once( './includes/footer.php' );
