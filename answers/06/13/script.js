const setErrorStyle = ( element ) =>
{
    // додавання CSS-класа з помилкою
    element.classList.add('error');
};

const setSuccessStyle = ( element ) =>
{
    // видалення CSS-класа з помилкою
    element.classList.remove('error');
};

const validateDatetime = ( datetime ) =>
{
    // валідація дати та часу за форматом "YYYY-MM-DDTHH:MM"
    return /^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])T(0[0-9]|1[0-9]|2[0-3])\:[0-5][0-9]$/.test( datetime.toString() );
};

// TODO: краще замінити на ...args, але може бути дуже складно у якості прикладу
const validate = ( startDate, elementStartDate, endDate, elementEndDate ) =>
{
    // змінна-"прапорець" для відсутності помилок
    let noErrors = true;

    // валідація полів з індикацієї помилки
    if( validateDatetime( startDate ) )
    {
        // змінюємо стилі відображення поля на коректні/валідні
        setSuccessStyle( elementStartDate );
    }
    else
    {
        // змінюємо стилі відображення поля на помилкові
        setErrorStyle( elementStartDate );
        noErrors = false;
    }

    // валідація полів з індикацієї помилки
    if( validateDatetime( endDate ) )
    {
        // змінюємо стилі відображення поля на коректні/валідні
        setSuccessStyle( elementEndDate );
    }
    else
    {
        // змінюємо стилі відображення поля на помилкові
        setErrorStyle( elementEndDate );
        noErrors = false;
    }

    if( startDate > endDate )
    {
        // змінюємо стилі відображення поля на помилкові
        setErrorStyle( elementEndDate );
        noErrors = false;
    }

    return noErrors;
};

const convertSecondsToDaysHoursMinutesSeconds = ( seconds ) =>
{
    const converted =
    {
        'days'      : 0,
        'hours'     : 0,
        'minutes'   : 0,
        'seconds'   : 0
    };

    converted.days      = Math.floor( seconds / 86400 );
    converted.hours     = Math.floor( seconds % 86400 / 3600 );
    converted.minutes   = Math.floor( seconds % 86400 % 3600 / 60 );
    converted.seconds   = Math.floor( seconds % 86400 % 3600 % 60 );

    return converted;
};

const getPluralForm = ( number, forms ) =>
{
    // кількість форм слова має бути 3
    if( forms.length < 3 )
    {
        return '';
    }

    // беремо останню цифру та перетворюємо на Int
    const numberLastOneDigit  = parseInt( number.toString().slice( -1 ) );
    // беремо останні 2 цифри та перетворюємо на Int
    const numberLastTwoDigits = parseInt( number.toString().slice( -2 ) );

    let index = 2;                                                      // за замовчуванням, всі, що не враховані в if-elseif-elseif нижче

    if( numberLastTwoDigits >= 10 && numberLastTwoDigits <= 20 )        // 10, 11, ..., 20, ..., XXXX10, XXXX11, ...
    {
        index = 2;
    }
    else if( numberLastOneDigit === 1 )                                 // 1, 21, 31, 41,..., XXXX1, XXX21, ...
    {
        index = 0;
    }
    else if( numberLastOneDigit >= 2 && numberLastOneDigit <= 4 )       // 2, 3, 4, ..., XXXX2, XXX3, XXX4, ...
    {
        index = 1;
    }

    return forms[ index ];
};

const getDateFormatted = ( objectDate ) =>
{
    // отримання дати та часу у форматі "DD.MM.YYYY HH:MM"
    return ( "0" + objectDate.getDate() ).slice( -2 ) + "." + ( "0" + ( objectDate.getMonth() + 1 ) ).slice( -2 ) + "." + objectDate.getFullYear()
        + " " + ( "0" + objectDate.getHours() ).slice( -2 ) + ":" + ( "0" + objectDate.getMinutes() ).slice( -2 );
};

const getResultDiffText = ( diff ) =>
{
    const chunks = [];

    if( diff.days && diff.days > 0 )
    {
        // додаємо дні
        chunks.push(
            diff.days + ' ' + getPluralForm( diff.days, [ 'день', 'дні', 'днів' ] )
        );
    }

    if( diff.hours && diff.hours > 0 )
    {
        // додаємо години
        chunks.push(
            diff.hours + ' ' + getPluralForm( diff.hours, [ 'година', 'години', 'годин' ] )
        );
    }

    if( diff.minutes && diff.minutes > 0 )
    {
        // додаємо хвилини
        chunks.push(
            diff.minutes + ' ' + getPluralForm( diff.minutes, [ 'хвилина', 'хвилини', 'хвилин' ] )
        );
    }

    if( diff.seconds && diff.seconds > 0 )
    {
        // додаємо секунди
        chunks.push(
            diff.seconds + ' ' + getPluralForm( diff.seconds, [ 'секунда', 'секунди', 'секунд' ] )
        );
    }

    // якщо виводити більше 1 елемента, то необхідно перед останнім елементом заміть "," відображати "та"
    return chunks.length > 1
        ? chunks.slice( 0, -1 ).join( ', ' ) + ' та ' + chunks.slice( -1 ).join()
        : chunks.join();
};

const datetimeDifferenceShowResult = ( objectStartDate, objectEndDate, diff ) =>
{
    if( document.getElementById('result') )
    {
        // видаляємо блок, якщо він існував
        document.getElementById('result').remove();
    }

    const elementResult = document.createElement('div');
    elementResult.setAttribute( 'id', 'result' );

    const elementResultTitle = document.createElement('div');
    elementResultTitle.setAttribute( 'class', 'title' );
    elementResultTitle.append( document.createTextNode( "Різниця між датами" ) );

    const elementResultDiff = document.createElement('div');
    elementResultDiff.setAttribute( 'class', 'diff' );

    const
        startDate  = getDateFormatted( objectStartDate ),
        endDate    = getDateFormatted( objectEndDate );

    const elementResultDiffStart = document.createElement( 'strong' );
    elementResultDiffStart.append( document.createTextNode( startDate ) );

    const elementResultDiff_end   = document.createElement( 'strong' );
    elementResultDiff_end.append( document.createTextNode( endDate ) );

    const result_text = getResultDiffText( diff );

    elementResultDiff.append(
        document.createTextNode( "Різниця між датами " ),
        elementResultDiffStart,
        document.createTextNode( " та " ),
        elementResultDiff_end,
        document.createElement( 'br' ),
        document.createTextNode( "становить " + result_text )
    );

    elementResult.append(
        elementResultTitle,
        elementResultDiff
    );

    document.getElementById('datetime-diff').after( elementResult );
};

const datetimeDifferenceCalculate = () =>
{
    // отримання елементів
    const elementStartDate    = document.getElementById('start-date');
    const elementEndDate      = document.getElementById('end-date');

    if( !elementStartDate || !elementEndDate )
    {
        // елементи відсутні, перериваємо виконання функції
        return false;
    }

    // отримання значень
    const startDate            = elementStartDate.value;
    const endDate              = elementEndDate.value;

    // перевірка коректності заповнення полів
    if( ! validate( startDate, elementStartDate, endDate, elementEndDate ) )
    {
        // не пройшла, перериваємо виконання функції
        return false;
    }

    // при створенні обʼєкта Date автоматично буде виконано парсинг та додаткова валідація дат
    // типу "31.02.2021", що автоматично перетвориться на "02.03.2021"
    const objectStartDate = new Date( startDate );
    const objectEndDate   = new Date( endDate );

    const diffSeconds      = ( objectEndDate - objectStartDate ) / 1000;

    // різниця відсутня, сприймаємо як помилку
    if( diffSeconds === 0 )
    {
        // відображаємо поля з помилками
        setErrorStyle( elementStartDate );
        setErrorStyle( elementEndDate );

        // перериваємо виконання функції
        return false;
    }

    // отримання обʼєкта з окремими значення різниці у днях, годинах, хвилинах, секундах
    const diffConverted    = convertSecondsToDaysHoursMinutesSeconds( diffSeconds );

    // відображення результату розрахунку
    datetimeDifferenceShowResult( objectStartDate, objectEndDate, diffConverted );

    // "return false" необхідне для зупинення процедури Submit форми
    return false;
};

const form      = document.getElementById('datetime-diff-form');
// обробка submit форми (натиснення Enter спричине Submit форми, бо присутній input type=submit)
form.onsubmit   = datetimeDifferenceCalculate;

const buttonCalc   = document.getElementById('calculate');
// обробка натискання на кнопку "Розрахувати різницю"
buttonCalc.onclick = datetimeDifferenceCalculate;