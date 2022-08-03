import { utilsResponse } from "./lib/utilsResponse";
import { utilsUser }     from "./lib/utilsUser";

/*
 [x] Відкривати http://localhost:8888/ на перевіряти що відображено 3 поста
 [x] Завантажувати наступну сторінку, кліком на "Load More" та перевіряти відповідь (має бути 3 поста) та сумарна кількість постів на сторінці 6
 [ ] Перейти в будь-який з довантажених постів, перевірити що відкрився потрібний (перевіркою за назвою та URL) і він не містить 404

 Всі тести мають містити:
 - Перевірку на HTTP код 200 для всіх сторінок
 - Контроль на повернення типу (content-type) HTML або JSON (в залежності від сторінки)
 - Контроль за часом завантаження сторінки (не більше за 1 сек)
 - Виконувати зняття скріншотів у ключових точках

 */
describe(
    'Не авторизовано (анонім)',
    {},
    () =>
    {
        let baseUrl;

        before( () =>
        {
            baseUrl = Cypress.env( 'url' ).toString();
        } );

        it( 'Відкрити першу сторінку, перевірити що відображено 3 записи', () =>
        {
            cy.visit( baseUrl );

            cy.get( 'main.main-posts > div.posts article' )
                .should( 'be.visible' )
                .should( 'have.length', 3 );

            //cy.screenshot();
        } );

        it( 'Відкрити першу сторінку, довантажити пости через "Load More"', () =>
        {
            cy.visit( baseUrl );

            cy.intercept( 'GET', '**/posts_ajax.php?page=2' ).as( 'postsLoadMore' );

            cy.get( '#load-more' )
                .should( 'be.visible' )
                .click();

            cy.wait( '@postsLoadMore' ).should(
                ( { request, response } ) =>
                {
                    utilsResponse.checkJSON( response );
                    expect( response.body ).to.have.length( 3 );
                }
            );

            cy.get( 'main.main-posts > div.posts article' )
                .should( 'be.visible' )
                .should( 'have.length', 6 );

            //cy.screenshot();
        } );

        it( 'Відкрити першу сторінку, довантажити пости через "Load More", відкрити будь-який та перевірити його', () =>
        {
            cy.visit( baseUrl );

            cy.intercept( 'GET', '**/posts_ajax.php?page=2' ).as( 'postsLoadMore' );

            cy.get( '#load-more' )
                .should( 'be.visible' )
                .click();

            cy.wait( '@postsLoadMore' ).should(
                ( { request, response } ) =>
                {
                    utilsResponse.checkJSON( response );
                    expect( response.body ).to.have.length( 3 );
                }
            );

            // let articleTitle;
            // cy.get('main.main-posts > div.posts > article:nth-child(5) > a.title > h2')
            //     .then( val => articleTitle = Cypress.$(val).text() );
            // cy.log(articleTitle);
            // cy.log( Cypress.$('main.main-posts > div.posts > article:nth-child(5) > a.title > h2').text() );

            cy.get( 'main.main-posts > div.posts > article:nth-child(5) > a.title' )
                .should( 'be.visible' )
                .click();

            const articleUrl = '/single.php?id=4';          // TODO
            const articleTitle = 'Xbox Design Lab Returns, Supports Next-Gen Controller Designs';       // TODO

            cy.url().should( 'include', articleUrl );

            cy.get( 'main > article div > h1' )
                .should( 'contain.text', articleTitle );

            //cy.screenshot();
        } );
    }
);

/*
 [x] Переходити за посиланням на "Login", що знаходиться в блоці "User Area" внизу сторінки
 [x] Виконувати авторизацію будь-яким з існуючих користувачів (випадковим чином)
 [x] Перевіряти імʼя авторизованого користувача (firstname), що знаходиться в блоці "User Area" внизу сторінки
 [x] Переходити на сторінку додавання категорії, кліком на "Add Category", що знаходиться в блоці "User Area" внизу сторінки
 [x] Переходити на сторінку додавання поста, кліком на "Add Post", що знаходиться в блоці "User Area" внизу сторінки
 [x] Додавати новий пост з вкладенням (зображенням) та активним статусом
 [x] Переходити на першу сторінку та шукати його як №1 у списку постів
 [ ] Відкривати цей доданий пост та перевірити що відкрився потрібний і він не містить 404
 [x] Виконувати деавторизацію, кліком на "Logout", що знаходиться в блоці "User Area" внизу сторінки

 Всі тести мають містити:
 - Перевірку на HTTP код 200 для всіх сторінок
 - Контроль на повернення типу (content-type) HTML або JSON (в залежності від сторінки)
 - Контроль за часом завантаження сторінки (не більше за 1 сек)
 - Виконувати зняття скріншотів у ключових точках

 */
describe(
    'Авторизовано',
    {},
    () =>
    {
        let baseUrl;

        before( () =>
        {
            baseUrl = Cypress.env( 'url' ).toString();
        } );

        beforeEach( () =>
        {
            // авторизація (або відновлення сесії)
            utilsUser.authorize();

            cy.visit( baseUrl );
        } );

        it( 'Відкрити першу сторінку, перевірити що відображено 3 записи', () =>
        {
            cy.get( 'main.main-posts > div.posts article' )
                .should( 'be.visible' )
                .should( 'have.length', 3 );

            //cy.screenshot();
        } );

        it( 'Перейти на сторінку додавання категорії та додати її', () =>
        {
            cy.get( 'footer .nav #user-area-add-category' )
                .should( 'be.visible' )
                .click();

            const
                currentTimestamp = ( new Date().getTime() ),
                categoryName = 'Test Category #' + currentTimestamp,
                categoryAlias = 'test-category-' + currentTimestamp,
                categorySuccessMessage = 'Категорію "' + categoryName + '" успішно додано';

            cy.get( '.main-form #form-category-title' )
                .should( 'be.visible' )
                .focus()
                .type( categoryName, { delay: 10 } )
                .should( 'have.value', categoryName );

            cy.get( '.main-form #form-category-alias' )
                .should( 'be.visible' )
                .focus()
                .should( 'have.value', categoryAlias );

            cy.get( '.main-form #form-category-submit' )
                .should( 'be.visible' )
                .focus()
                .click();

            cy.get( '.main-form .success-general-text' )
                .should( 'contain', categorySuccessMessage );

            //cy.screenshot();
        } );

        it( 'Перейти на сторінку додавання поста, додати його та відкрити', () =>
        {
            cy.get( 'footer .nav #user-area-add-post' )
                .should( 'be.visible' )
                .click();

            const
                currentTimestamp = ( new Date().getTime() ),
                postTitle = 'Тестовий запис #' + currentTimestamp,
                postDescription = `Тестовий опис для тестового запису #${ currentTimestamp }`,
                postContent = `<p>Тестовий контент для тестового запису #${ currentTimestamp }</p>`,
                postDate = '2022-08-02T08:55',
                postCategoryTitle = 'PC',
                postCategoryValue = 1,
                postFilePath = 'example_attachments/01.jpg',
                postStatusTitle = 'Активний',
                postStatusValue = 201;

            cy.get( '.main-form #form-post-title' )
                .should( 'be.visible' )
                .focus()
                .type( postTitle, { delay: 10 } )
                .should( 'have.value', postTitle );

            cy.get( '.main-form #form-post-description' )
                .should( 'be.visible' )
                .focus()
                .type( postDescription, { delay: 10 } )
                .should( 'have.value', postDescription );

            cy.get( '.main-form #form-post-content' )
                .should( 'be.visible' )
                .focus()
                .type( postContent, { delay: 10 } )
                .should( 'have.value', postContent );

            cy.get( '.main-form #form-post-publish-date' )
                .should( 'be.visible' )
                .focus()
                .type( postDate, { delay: 10 } )
                .should( 'have.value', postDate );

            cy.get( '.main-form #form-post-category' )
                .should( 'be.visible' )
                .focus()
                .select( postCategoryTitle )
                .should( 'have.value', postCategoryValue );

            cy.get( '.main-form #form-post-cover' )
                .should( 'be.visible' )
                .focus()
                .selectFile( postFilePath );

            cy.get( '.main-form #form-post-status' )
                .should( 'be.visible' )
                .focus()
                .select( postStatusTitle )
                .should( 'have.value', postStatusValue );

            cy.get( '.main-form #form-post-submit' )
                .should( 'be.visible' )
                .focus()
                .click();

            //cy.screenshot();

            cy.get( 'main > article div > h1' )
                .should( 'contain', postTitle );

            cy.visit( baseUrl );

            cy.get( 'main.main-posts > div.posts > article:nth-child(1) > a.title' )
                .should( 'be.visible' )
                .should( 'contain', postTitle )
                .click();
        } );

        after(
            () =>
            {
                // Деавторизація
                utilsUser.deAuthorize();
            }
        );
    }
);
