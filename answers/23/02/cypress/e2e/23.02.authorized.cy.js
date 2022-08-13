import { utilsResponse } from "./lib/utilsResponse";
import { utilsUser }     from "./lib/utilsUser";

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
 [x] Перевірку на HTTP код 200 для всіх сторінок
 [x] Контроль на повернення типу (content-type) HTML або JSON (в залежності від сторінки)
 [x] Контроль за часом завантаження сторінки (не більше за 1 сек)
 [x] Виконувати зняття скріншотів у ключових точках

 */
describe(
    'Авторизовано',
    {},
    () =>
    {
        let
            baseUrl,
            timeoutDefault,
            userAuthorizeData;

        before( () =>
        {
            baseUrl     = Cypress.env( 'url' ).toString();
            timeoutDefault  = parseInt( Cypress.env( 'timeouts' ).default );

            const users = Cypress.env( 'users' );
            userAuthorizeData = users[ Math.floor( Math.random() * users.length ) ];
        } );

        beforeEach( () =>
        {
            // авторизація (або відновлення сесії)
            utilsUser.authorize( userAuthorizeData );

            cy.visit(
                baseUrl,
                {
                    timeout: timeoutDefault
                }
            );

            cy.document()
                .its( 'contentType' ).should( 'eq', 'text/html' );
        } );

        it( 'Авторизуватись', () =>
        {

        } );

        it( 'Відкрити першу сторінку, перевірити що відображено 3 записи', () =>
        {
            cy.get( 'main.main-posts > div.posts article' )
                .should( 'be.visible' )
                .should( 'have.length', 3 );

            // cy.screenshot( { overwrite: true } );
        } );

        it( 'Перейти на сторінку додавання категорії та додати її', () =>
        {
            cy.intercept( 'GET', '**/category_add.php' )
                .as( 'categoryAddPage' );

            cy.get( 'footer .nav #user-area-add-category' )
                .should( 'be.visible' )
                .click();

            let pageLoadStart = new Date().getTime();

            cy.wait( '@categoryAddPage' )
                .should( ( { response } ) =>
                    utilsResponse.checkTimeoutContentTypeStatus(
                        response, pageLoadStart, timeoutDefault
                    )
                );

            cy.intercept( 'POST', '**/category_add.php' )
                .as( 'categoryAddPagePost' );

            const
                currentTimestamp = new Date().getTime(),
                categoryName = 'Test Category #' + currentTimestamp,
                categoryAlias = 'test-category-' + currentTimestamp,
                categorySuccessMessage = 'Категорію "' + categoryName + '" успішно додано';

            cy.get( '.main-form #form-category-title' )
                .should( 'be.visible' )
                .focus()
                .clear()
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

            pageLoadStart = new Date().getTime();

            cy.wait( '@categoryAddPagePost' )
                .should( ( { response } ) =>
                    utilsResponse.checkTimeoutContentTypeStatus(
                        response, pageLoadStart, timeoutDefault
                    )
                );

            cy.get( '.main-form .success-general-text' )
                .should( 'contain', categorySuccessMessage );

            // cy.screenshot( { overwrite: true } );
        } );

        it( 'Перейти на сторінку додавання поста, додати його та відкрити', () =>
        {
            cy.intercept( 'GET', '**/post_add.php' )
                .as( 'postAddPage' );

            cy.get( 'footer .nav #user-area-add-post' )
                .should( 'be.visible' )
                .click();

            let pageLoadStart = new Date().getTime();

            cy.wait( '@postAddPage' )
                .should( ( { response } ) =>
                    utilsResponse.checkTimeoutContentTypeStatus(
                        response, pageLoadStart, timeoutDefault
                    )
                );

            cy.intercept( 'POST', '**/post_add.php' )
                .as( 'postAddPagePost' );

            const
                currentTimestamp = new Date().getTime(),
                postTitle = 'Тестовий запис #' + currentTimestamp,
                postDescription = `Тестовий опис для тестового запису #${ currentTimestamp }`,
                postContent = `<p>Тестовий контент для тестового запису #${ currentTimestamp }</p>`,
                postDate = '2022-08-02T08:55',
                postCategoryTitle = 'PC',
                postCategoryValue = 1,
                postFilePath = 'example_attachments/01.jpg',
                postStatusTitle = 'Активний',
                postStatusValue = 201;

            cy.get( '#form-post-title' )
                .should( 'be.visible' )
                .focus()
                .type( postTitle, { delay: 10 } )
                .should( 'have.value', postTitle );

            cy.get( '#form-post-description' )
                .should( 'be.visible' )
                .focus()
                .type( postDescription, { delay: 10 } )
                .should( 'have.value', postDescription );

            cy.get( '#form-post-content' )
                .should( 'be.visible' )
                .focus()
                .type( postContent, { delay: 10 } )
                .should( 'have.value', postContent );

            cy.get( '#form-post-publish-date' )
                .should( 'be.visible' )
                .focus()
                .type( postDate, { delay: 10 } )
                .should( 'have.value', postDate );

            cy.get( '#form-post-category' )
                .should( 'be.visible' )
                .focus()
                .select( postCategoryTitle )
                .should( 'have.value', postCategoryValue );

            cy.get( '#form-post-cover' )
                .should( 'be.visible' )
                .focus()
                .selectFile( postFilePath );

            cy.get( '#form-post-status' )
                .should( 'be.visible' )
                .focus()
                .select( postStatusTitle )
                .should( 'have.value', postStatusValue );

            cy.get( '#form-post-submit' )
                .should( 'be.visible' )
                .focus()
                .click();

            const postAddTimeout = parseInt( Cypress.env( 'timeouts' ).postAdd );

            cy.wait( '@postAddPagePost' )
                .should( ( { response } ) =>
                    utilsResponse.checkTimeoutContentTypeStatus(
                        response, pageLoadStart, postAddTimeout, 302
                    )
                );

            // cy.screenshot( { overwrite: true } );

            cy.get( 'main > article div > h1' )
                .should( 'contain', postTitle );

            cy.visit(
                baseUrl,
                {
                    timeout: timeoutDefault
                }
            );

            cy.get( 'main.main-posts > div.posts > article:nth-child(1) > a.title' )
                .should( 'be.visible' )
                .should( 'contain', postTitle )
                .click();

            // TODO: page load time
            // TODO: http status
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
