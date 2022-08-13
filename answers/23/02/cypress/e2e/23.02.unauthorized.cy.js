import { utilsResponse } from "./lib/utilsResponse";
import { utilsUser }     from "./lib/utilsUser";

/*
 [x] Відкривати http://localhost:8888/ на перевіряти що відображено 3 поста
 [x] Завантажувати наступну сторінку, кліком на "Load More" та перевіряти відповідь (має бути 3 поста) та сумарна кількість постів на сторінці 6
 [ ] Перейти в будь-який з довантажених постів, перевірити що відкрився потрібний (перевіркою за назвою та URL) і він не містить 404

 Всі тести мають містити:
 [x] Перевірку на HTTP код 200 для всіх сторінок
 [x] Контроль на повернення типу (content-type) HTML або JSON (в залежності від сторінки)
 [x] Контроль за часом завантаження сторінки (не більше за 1 сек)
 [x] Виконувати зняття скріншотів у ключових точках

 */
describe(
    'Не авторизовано (анонім)',
    {},
    () =>
    {
        let baseUrl,
            timeoutDefault;

        before( () =>
        {
            baseUrl     = Cypress.env( 'url' ).toString();
            timeoutDefault  = parseInt( Cypress.env( 'timeouts' ).default );
        } );

        beforeEach( () =>
        {
            cy.visit(
                baseUrl,
                {
                    timeout: timeoutDefault
                }
            );

            cy.document()
                .its( 'contentType' ).should( 'eq', 'text/html' );
        });

        it( 'Відкрити першу сторінку, перевірити що відображено 3 записи', () =>
        {
            cy.get( 'main.main-posts > div.posts article' )
                .should( 'be.visible' )
                .should( 'have.length', 3 );

            // cy.screenshot( { overwrite: true } );
        } );

        it( 'Відкрити першу сторінку, довантажити пости через "Load More"', () =>
        {
            cy.intercept( 'GET', '**/posts_ajax.php?page=2' )
                .as( 'postsLoadMore' );

            cy.get( '#load-more' )
                .should( 'be.visible' )
                .click();

            cy.wait( '@postsLoadMore' ).should(
                ( { request, response } ) =>
                {
                    utilsResponse.checkHttpStatus( response );
                    utilsResponse.checkJSON( response );
                    expect( response.body ).to.have.length( 3 );
                }
            );

            cy.get( 'main.main-posts > div.posts article' )
                .should( 'be.visible' )
                .should( 'have.length', 6 );

            // cy.screenshot( { overwrite: true } );
        } );

        it( 'Відкрити першу сторінку, довантажити пости через "Load More", відкрити будь-який та перевірити його', () =>
        {
            cy.intercept( 'GET', '**/posts_ajax.php?page=2' ).as( 'postsLoadMore' );

            cy.get( '#load-more' )
                .should( 'be.visible' )
                .click();

            cy.wait( '@postsLoadMore' ).should(
                ( { request, response } ) =>
                {
                    utilsResponse.checkHttpStatus( response );
                    utilsResponse.checkJSON( response );
                    expect( response.body ).to.have.length( 3 );
                }
            );

            cy.get( 'main.main-posts > div.posts > article:nth-child(5) > a.title' )
                .then(
                    ( el ) =>
                    {
                        return {
                            'url'  : el.get(),
                            'title': el.text(),
                        };
                    }
                ).then(
                    ( articleData ) =>
                    {
                        cy.get( 'main.main-posts > div.posts > article:nth-child(5) > a.title' )
                            .should( 'be.visible' )
                            .click();

                        cy.get( 'main > article div > h1' )
                            .should( 'contain.text', articleData.title );

                        cy.url()
                            .should( 'include', articleData.url )

                        // cy.screenshot( { overwrite: true } );
                    }
                );
        } );
    }
);
