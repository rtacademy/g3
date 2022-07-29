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

// обробка авторизації
$authController    = new \lib\controllers\AuthController();
$authController->login();

// головне меню
$websiteMenuModel  = new \lib\models\WebsiteMenuModel();
$websiteMenuItems  = $websiteMenuModel->getList();

require_once( './includes/header.php' );

?>
<main class="main-form">
    <h1>Авторизація</h1>

    <?php

    // обробка існування помилки при авторизації та її відображення
    $errorMessage = $authController->getErrorMessage();

    if( !empty( $errorMessage ) )
    {
        echo( '<div class="error-general-text">' . $errorMessage . '</div>' );
    }

    ?>

    <form method="post" name="user" action="" class="form" id="form-user">
        <ul>
            <li><label for="form-user-login">Логін<span>*</span></label></li>
            <li>
                <input type="text" minlength="2" maxlength="32" value="" name="login" id="form-user-login" required placeholder="Введіть логін">
            </li>

            <li><label for="form-user-password">Пароль<span>*</span></label></li>
            <li>
                <input type="password" minlength="8" maxlength="32" value="" name="password" id="form-user-password" required placeholder="Введіть пароль" autocomplete="off">
            </li>

            <li><button type="submit" name="submit" id="form-user-submit">Надіслати</button></li>
        </ul>
    </form>
</main>

<script>
const validate =
{
    'initialize' : () =>
    {
        // видалення блоків з текстом помилок
        document.querySelectorAll('#form-user div.error-text').forEach( ( element ) => element.remove() );

        // видалення класів valid та invalid
        document.querySelectorAll('#form-user .valid, #form-user .invalid').forEach(
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

    'login' : ( value, element ) =>
    {
        const
            minLength   = 2,
            maxLength   = 32,
            regExp      = /^[a-z0-9\.\-\_]+$/i;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Логін"';
        }
        else if( value.length > maxLength )
        {
            errorText = 'Максимальна довжина поля "Логін" - ' + maxLength + ' символів';
        }
        else if( ! regExp.test( value ) )
        {
            errorText = 'Поле "Логін" містить некоректні символи';
        }

        return validate._showHideErrors( element, errorText );
    },

    'password' : ( value, element ) =>
    {
        const
            minLength   = 8,
            maxLength   = 32,
            regExp      = /^[A-ZА-ЯЫЁЭҐІЇЄ0-9`ʼ~!@"#№\$%\^&\*\(\)-_\+=\{\}\[\]\|\?\/\.,':;<>\s]+$/iu;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Пароль"';
        }
        else if( value.length > maxLength )
        {
            errorText = 'Максимальна довжина поля "Пароль" - ' + maxLength + ' символів';
        }
        else if( ! regExp.test( value ) )
        {
            errorText = 'Поле "Пароль" містить некоректні символи';
        }

        return validate._showHideErrors( element, errorText );
    }
};

const formGetElementsAndValues = () =>
{
    // отримання елементів
    const element = {};
    element.login           = document.getElementById( 'form-user-login' );
    element.password        = document.getElementById( 'form-user-password' );

    // отримання значень
    const value = {};
    value.login             = ( element.login.value || '' ).toString();
    value.password          = ( element.password.value || '' ).toString();

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

// обробка submit форми (натиснення Enter спричине Submit форми, бо присутній input type=submit)
document.getElementById('form-user').onsubmit = formSubmit;

// обробка натискання на кнопку "Надіслати"
document.getElementById('form-user-submit').onclick = formSubmit;
</script>
<?php

require_once( './includes/footer.php' );
