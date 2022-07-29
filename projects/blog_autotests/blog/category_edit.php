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
$categoryId = intval( preg_replace( '#[^0-9]#', '', $_GET['id'] ?? '0' ) );

// обробка форми редагування категорії
$categoryController    = new \lib\controllers\CategoryController();
$category = $categoryController->edit( $categoryId );

// головне меню
$websiteMenuModel  = new \lib\models\WebsiteMenuModel();
$websiteMenuItems  = $websiteMenuModel->getList();

require_once( './includes/header.php' );

?>
<main class="main-form">
    <h1>Редагування категорії <?= $category?->getTitle() ?? '' ?></h1>

    <?php

    // обробка існування помилки при заповненні форми та її відображення
    $errorMessage = $categoryController->getErrorMessage();

    if( !empty( $errorMessage ) )
    {
        echo( '<div class="error-general-text">' . $errorMessage . '</div>' );
    }

    // обробка існування тексту з успішним повідомленням
    $successMessage = $categoryController->getSuccessMessage();

    if( !empty( $successMessage ) )
    {
        echo( '<div class="success-general-text">' . $successMessage . '</div>' );
    }

    if( !empty( $category ) )
    {

    ?>
    <form method="post" name="category" action="" class="form" id="form-category">
        <ul>
            <li><label for="form-category-title">Назва<span>*</span></label></li>
            <li>
                <input type="text" minlength="2" maxlength="32" value="<?= $category->getTitle() ?? '' ?>" name="title" id="form-category-title" required placeholder="Введіть назву категорії">
            </li>

            <li><label for="form-category-alias">Аліас<span>*</span></label></li>
            <li>
                <input type="text" minlength="2" maxlength="32" value="<?= $category->getAlias() ?? '' ?>" name="alias" id="form-category-alias" required placeholder="Введіть аліас категорії">
            </li>

            <li><button type="submit" name="submit" id="form-category-submit">Надіслати</button></li>
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
        document.querySelectorAll('#form-category div.error-text').forEach( ( element ) => element.remove() );

        // видалення класів valid та invalid
        document.querySelectorAll('#form-category .valid, #form-category .invalid').forEach(
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
            maxLength   = 32,
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

    'alias' : ( value, element ) =>
    {
        const
            minLength   = 2,
            maxLength   = 32,
            regExp      = /^[a-z0-9-]+$/;

        let errorText = '';

        if( value.length < minLength )
        {
            errorText = 'Необхідно заповнити поле "Аліас"';
        }
        else if( value.length > maxLength )
        {
            errorText = 'Максимальна довжина поля "Аліас" - ' + maxLength + ' символів';
        }
        else if( ! regExp.test( value ) )
        {
            errorText = 'Поле "Аліас" містить некоректні символи';
        }

        return validate._showHideErrors( element, errorText );
    }
};

const formGetElementsAndValues = () =>
{
    // отримання елементів
    const element = {};
    element.title           = document.getElementById( 'form-category-title' );
    element.alias           = document.getElementById( 'form-category-alias' );

    // отримання значень
    const value = {};
    value.title             = ( element.title.value || '' ).toString();
    value.alias             = ( element.alias.value || '' ).toString();

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

const formGenerateAlias = () =>
{
    // отримання елементів та їх значень
    const { element, value }        = formGetElementsAndValues();
    // цей спосіб називається "Деструктуризація"
    // https://learn.javascript.ru/destructuring-assignment#destrukturizatsiya-obekta

    element.alias.value =
        transliteration( value.title )              // транслітеруємо кирилицю в латиницю (за правилами транслітерації української мови)
        .replace( /[^0-9a-z]/ig, '-' )              // заміняємо всі інши символи на "-"
        .replace( /(-{2,})/g, '-' )                 // блоки по 2 і більше "-" на 1 "-"
        .replace( /^-/, '' )                        // видаляємо початкові "-"
        .replace( /-$/, '' )                        // видаляємо кінцеві "-"
        .substring( 0, 32 );                        // залишаємо перші 32 символи

    // видаляємо всі елементи з помилками, червоні та зелені рамки
    validate.initialize();

    // перевалідуємо поле "Аліас"
    validate.alias( element.alias.value, element.alias );
};

const transliteration = ( inputText ) =>
{
    // спізжено з https://dmsu.gov.ua/services/transliteration.html

    const rules =
    [
        { 'pattern': 'а', 'replace': 'a' },
        { 'pattern': 'б', 'replace': 'b' },
        { 'pattern': 'в', 'replace': 'v' },
        { 'pattern': 'зг', 'replace': 'zgh' },
        { 'pattern': 'Зг', 'replace': 'Zgh' },
        { 'pattern': 'г', 'replace': 'h' },
        { 'pattern': 'ґ', 'replace': 'g' },
        { 'pattern': 'д', 'replace': 'd' },
        { 'pattern': 'е', 'replace': 'e' },
        { 'pattern': '^є', 'replace': 'ye' },
        { 'pattern': 'є', 'replace': 'ie' },
        { 'pattern': 'ж', 'replace': 'zh' },
        { 'pattern': 'з', 'replace': 'z' },
        { 'pattern': 'и', 'replace': 'y' },
        { 'pattern': 'і', 'replace': 'i' },
        { 'pattern': '^ї', 'replace': 'yi' },
        { 'pattern': 'ї', 'replace': 'i' },
        { 'pattern': '^й', 'replace': 'y' },
        { 'pattern': 'й', 'replace': 'i' },
        { 'pattern': 'к', 'replace': 'k' },
        { 'pattern': 'л', 'replace': 'l' },
        { 'pattern': 'м', 'replace': 'm' },
        { 'pattern': 'н', 'replace': 'n' },
        { 'pattern': 'о', 'replace': 'o' },
        { 'pattern': 'п', 'replace': 'p' },
        { 'pattern': 'р', 'replace': 'r' },
        { 'pattern': 'с', 'replace': 's' },
        { 'pattern': 'т', 'replace': 't' },
        { 'pattern': 'у', 'replace': 'u' },
        { 'pattern': 'ф', 'replace': 'f' },
        { 'pattern': 'х', 'replace': 'kh' },
        { 'pattern': 'ц', 'replace': 'ts' },
        { 'pattern': 'ч', 'replace': 'ch' },
        { 'pattern': 'ш', 'replace': 'sh' },
        { 'pattern': 'щ', 'replace': 'shch' },
        { 'pattern': 'ьо', 'replace': 'io' },
        { 'pattern': 'ьї', 'replace': 'ii' },
        { 'pattern': 'ь', 'replace': '' },
        { 'pattern': '^ю', 'replace': 'yu' },
        { 'pattern': 'ю', 'replace': 'iu' },
        { 'pattern': '^я', 'replace': 'ya' },
        { 'pattern': 'я', 'replace': 'ia' },
        { 'pattern': 'А', 'replace': 'A' },
        { 'pattern': 'Б', 'replace': 'B' },
        { 'pattern': 'В', 'replace': 'V' },
        { 'pattern': 'Г', 'replace': 'H' },
        { 'pattern': 'Ґ', 'replace': 'G' },
        { 'pattern': 'Д', 'replace': 'D' },
        { 'pattern': 'Е', 'replace': 'E' },
        { 'pattern': '^Є', 'replace': 'Ye' },
        { 'pattern': 'Є', 'replace': 'Ie' },
        { 'pattern': 'Ж', 'replace': 'Zh' },
        { 'pattern': 'З', 'replace': 'Z' },
        { 'pattern': 'И', 'replace': 'Y' },
        { 'pattern': 'І', 'replace': 'I' },
        { 'pattern': '^Ї', 'replace': 'Yi' },
        { 'pattern': 'Ї', 'replace': 'I' },
        { 'pattern': '^Й', 'replace': 'Y' },
        { 'pattern': 'Й', 'replace': 'I' },
        { 'pattern': 'К', 'replace': 'K' },
        { 'pattern': 'Л', 'replace': 'L' },
        { 'pattern': 'М', 'replace': 'M' },
        { 'pattern': 'Н', 'replace': 'N' },
        { 'pattern': 'О', 'replace': 'O' },
        { 'pattern': 'П', 'replace': 'P' },
        { 'pattern': 'Р', 'replace': 'R' },
        { 'pattern': 'С', 'replace': 'S' },
        { 'pattern': 'Т', 'replace': 'T' },
        { 'pattern': 'У', 'replace': 'U' },
        { 'pattern': 'Ф', 'replace': 'F' },
        { 'pattern': 'Х', 'replace': 'Kh' },
        { 'pattern': 'Ц', 'replace': 'Ts' },
        { 'pattern': 'Ч', 'replace': 'Ch' },
        { 'pattern': 'Ш', 'replace': 'Sh' },
        { 'pattern': 'Щ', 'replace': 'Shch' },
        { 'pattern': 'Ь', 'replace': '' },
        { 'pattern': '^Ю', 'replace': 'Yu' },
        { 'pattern': 'Ю', 'replace': 'Iu' },
        { 'pattern': '^Я', 'replace': 'Ya' },
        { 'pattern': 'Я', 'replace': 'Ia' },
        { 'pattern': '’', 'replace': '' },
        { 'pattern': '\'', 'replace': '' },
        { 'pattern': '`', 'replace': '' }
    ];

    const words = inputText.split( /[-_ \n]/ );

    for( let n in words )
    {
        let word = words[ n ];

        for( let ruleNumber in rules )
        {
            word = word.replace(
                new RegExp( rules[ ruleNumber ][ 'pattern' ], 'gm' ),
                rules[ ruleNumber ][ 'replace' ]
            );
        }

        inputText = inputText.replace( words[ n ], word );
    }

    return inputText.toLowerCase();
};

// обробка submit форми (натиснення Enter спричине Submit форми, бо присутній input type=submit)
document.getElementById('form-category').onsubmit = formSubmit;

// обробка натискання на кнопку "Надіслати"
document.getElementById('form-category-submit').onclick = formSubmit;

// обробка зміни значення поля title - генерація alias
document.getElementById('form-category-title').onchange = formGenerateAlias;
</script>
<?php

require_once( './includes/footer.php' );
