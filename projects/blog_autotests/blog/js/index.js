class Post {
    id;
    title;
    alias;
    description;
    author;
    publishDate;
    category;
    cover;
    url;

    constructor() {

    }
    getId() {
        return this.id;
    }
    setId( id ) {
        this.id = parseInt( id );
    }
    getTitle() {
        return this.title;
    }
    setTitle( title ) {
        this.title = title.toString();
    }
    getAlias() {
        return this.alias;
    }
    setAlias( alias ) {
        this.alias = alias.toString();
    }
    getDescription() {
        return this.description;
    }
    setDescription( description ) {
        this.description = description.toString();
    }
    getAuthor() {
        return this.author;
    }
    setAuthor( author ) {
        this.author = author instanceof Author ? author : null;
    }
    getPublishDate( format ) {
        switch( format )
        {
            case 'iso':
                return this.publishDate.toISOString();

            default:
                // отримання дати та часу у форматі "DD.MM.YYYY HH:MM"
                return ( "0" + this.publishDate.getDate() ).slice( -2 ) + "."
                    + ( "0" + ( this.publishDate.getMonth() + 1 ) ).slice( -2 ) + "."
                    + this.publishDate.getFullYear()
                    + " "
                    + ( "0" + this.publishDate.getHours() ).slice( -2 ) + ":"
                    + ( "0" + this.publishDate.getMinutes() ).slice( -2 );
        }
    }
    setPublishDate( publishDate ) {
        this.publishDate = publishDate instanceof Date ? publishDate : new Date( publishDate );
    }
    getCategory() {
        return this.category;
    }
    setCategory( category ) {
        this.category = category instanceof Category ? category : null;
    }
    getCover() {
        return this.cover;
    }
    setCover( cover ) {
        this.cover = cover instanceof Cover ? cover : null;
    }
    getUrl() {
        return this.url;
    }
    setUrl( url ) {
        this.url = url.toString();
    }
}

class User {
    id;
    firstName;
    lastName;

    constructor() {

    }
    getId(){
        return this.id;
    }
    setId( id ) {
        this.id = parseInt( id );
    }
    getFirstName() {
        return this.firstName;
    }
    setFirstName( firstName ) {
        this.firstName = firstName.toString();
    }
    getLastName() {
        return this.lastName;
    }
    setLastName( lastName ) {
        this.lastName = lastName.toString();
    }
}

class Author extends User {
    getUrl() {
        return './author.php?id=' + this.id;
    }
}

class Category {
    id;
    title;
    alias;

    constructor() {

    }
    getId(){
        return this.id;
    }
    setId( id ) {
        this.id = parseInt( id );
    }
    getTitle() {
        return this.title;
    }
    setTitle( title ) {
        this.title = title.toString();
    }
    getAlias() {
        return this.alias;
    }
    setAlias( alias ) {
        this.alias = alias.toString();
    }
    getUrl() {
        return './category.php?alias=' + this.alias;
    }
}

class Cover {
    src;
    srcset;
    sizes;
    alt;

    constructor() {

    }
    getSrc() {
        return this.src;
    }
    setSrc( src ) {
        this.src = src.toString();
    }
    getSrcSet() {
        return this.srcset;
    }
    setSrcSet( srcset ) {
        this.srcset = srcset.toString();
    }
    getSizes() {
        return this.sizes;
    }
    setSizes( sizes ) {
        this.sizes = sizes.toString();
    }
    getAlt() {
        return this.alt;
    }
    setAlt( alt ) {
        this.alt = alt.toString();
    }
}

const createPostInstance = ( data ) =>
{
    // створюємо екземпляр класа Cover та заповнюємо даними
    const cover = new Cover();
    cover.setSrc( data.cover.src );
    cover.setSrcSet( data.cover.srcset );
    cover.setSizes( data.cover.sizes );
    cover.setAlt( data.cover.alt || data.title );

    // створюємо екземпляр класа Category та заповнюємо даними
    const category = new Category();
    category.setId( data.category.id );
    category.setTitle( data.category.title );
    category.setAlias( data.category.alias );

    // створюємо екземпляр класа Post та заповнюємо даними
    const post = new Post();
    post.setId( data.id );
    post.setTitle( data.title );
    post.setAlias( data.alias );
    post.setDescription( data.description );
    post.setPublishDate( data.publish_date );
    post.setCover( cover );                     // встановлюємо заповнений даними екземпляр класа Cover у екземпляр класа Post
    post.setCategory( category );               // встановлюємо заповнений даними екземпляр класа Category у екземпляр класа Post
    post.setUrl( data.url );

    return post;
};

const createPostElement = ( post ) =>
{
    // створюємо елемент <article>
    const el_article       = document.createElement( 'article' );

    // створюємо елемент a.cover
    const el_a_cover       = document.createElement( 'a' );
    el_a_cover.setAttribute( 'class', 'cover' );
    el_a_cover.setAttribute( 'href', post.getUrl() );

    const el_a_cover_img   = document.createElement( 'img' );
    el_a_cover_img.setAttribute( 'src', post.getCover().getSrc() );
    el_a_cover_img.setAttribute( 'srcset', post.getCover().getSrcSet() );
    el_a_cover_img.setAttribute( 'sizes', post.getCover().getSizes() );
    el_a_cover_img.setAttribute( 'alt', post.getCover().getAlt() );

    el_a_cover.append( el_a_cover_img );

    // створюємо елемент a.title
    const el_a_title       = document.createElement( 'a' );
    el_a_title.setAttribute( 'class', 'title' );
    el_a_title.setAttribute( 'href', post.getUrl() );

    const el_a_title_h2   = document.createElement( 'h2' );
    el_a_title_h2.append(
        document.createTextNode( post.getTitle() )
    );

    el_a_title.append( el_a_title_h2 );

    // створюємо елемент time
    const el_time       = document.createElement( 'time' );
    el_time.setAttribute( 'datetime', post.getPublishDate( 'iso' ) );

    el_time.append(
        document.createTextNode(
            post.getPublishDate()
        )
    );

    // створюємо елемент a.description
    const el_a_description       = document.createElement( 'a' );
    el_a_description.setAttribute( 'class', 'description' );
    el_a_description.setAttribute( 'href', post.getUrl() );
    el_a_description.append(
        document.createTextNode( post.getDescription() )
    );

    // створюємо елемент a.more
    const el_a_more       = document.createElement( 'a' );
    el_a_more.setAttribute( 'class', 'more' );
    el_a_more.setAttribute( 'href', post.getUrl() );
    el_a_more.append(
        document.createTextNode( 'Continue Reading' )
    );

    // додаємо всі створені елементи до загального елементу <article>
    el_article.append(
        el_a_cover,
        el_a_title,
        el_time,
        el_a_description,
        el_a_more
    );

    // повертаємо елемент <article>
    return el_article;
};

const loadMorePosts = async ( event ) =>
{
    event.preventDefault();         // https://developer.mozilla.org/ru/docs/Web/API/Event/preventDefault
    // "Вызов preventDefault на любой стадии выполнения  потока событий отменяет событие, а это означает,
    // что любое действие по умолчанию обычно принимается реализацией, как  результат события, которое не произойдёт."

    let
        page        = parseInt( document.getElementById( 'load-more' ).getAttribute( 'data-current-page' ) ) || 1,
        maxPages    = parseInt( document.getElementById( 'load-more' ).getAttribute( 'data-max-pages' ) ) || 1;

    // додаткова перевірка одразу
    if( page > maxPages )
    {
        // видаляємо кнопку "Load More", бо ми знаємо що більше постів не буде
        document.getElementById('load-more').remove();
        return false;
    }

    // GET-запит для posts.json, що буде завантажуватись з поточного домена
    let response = await fetch( './posts_ajax.php?page=' + page );

    if( response.ok )           // HTTP = 200, все ОК
    {
        const postsData = await response.json();   // читаємо відповідь в форматі JSON

        if( !postsData )        // якщо дані відсутні
        {
            return false;
        }

        const postsElements = postsData.map(
            ( data ) =>
            {
                const post = createPostInstance( data );

                return createPostElement( post );
            }
        );

        // відображаємо створені елементи з масиву елементів postsElements
        document.getElementsByClassName('posts')[0].append( ...postsElements );
        // "..." - це оператор spread
        // https://learn.javascript.ru/rest-parameters-spread-operator#spread-operator
        // https://developer.mozilla.org/ru/docs/Web/JavaScript/Reference/Operators/Spread_syntax

        // збільшуємо поточну сторінку
        page++;

        if( page > maxPages )
        {
            // видаляємо кнопку "Load More", бо ми знаємо що більше постів не буде
            document.getElementById('load-more').remove();
        }
        else
        {
            // оновлюємо поточну сторінку
            document.getElementById( 'load-more' ).setAttribute( 'data-current-page', page );
        }
    }
    else
    {                       // HTTP не 200, обробляємо як помилку
        console.error( 'Сталася помилка ' + response.status + ': ' + response.statusText );
    }

    return false;       // і хоч у нас є на початку "event.preventDefault()", але для недобраузерів краще і явно повернути false також.
};

if( document.getElementById( 'load-more' ) )
{
    document.getElementById( 'load-more' ).onclick = loadMorePosts;
}