const utilsUser = {};

utilsUser.checkAuthCookie = () =>
{
    const cookieName = Cypress.env( 'cookieName' ).toString();

    cy.getCookie( cookieName )
        .should( 'exist' )
        .should( 'have.property', 'value' );
};

utilsUser.authorize = () =>
{
    const baseUrl = Cypress.env( 'url' ).toString();

    const users = Cypress.env( 'users' );
    // отримання авторизаційних даних випадкового користувача з доступних
    const { login, password, firstname } = users[ Math.floor( Math.random() * users.length ) ];

    cy.session( [ login, password ], () =>
    {
        cy.visit( baseUrl );

        cy.get( 'footer .nav #user-area-login' )
            .should( 'be.visible' )
            .click();

        cy.get( '.main-form #form-user-login' )
            .should( 'be.visible' )
            .focus()
            .type( login, { delay: 10 } )
            .should( 'have.value', login );

        cy.get( '.main-form #form-user-password' )
            .should( 'be.visible' )
            .focus()
            .type( password, { delay: 10 } )
            .should( 'have.value', password );

        cy.get( '.main-form #form-user-submit' )
            .should( 'be.visible' )
            .focus()
            .click();

        cy.get( 'footer .nav ul li:first-child' )
            .contains( firstname );

        // перевірка існування авторизаційної кукі
        utilsUser.checkAuthCookie();
    } );
};

utilsUser.deAuthorize = () =>
{
    const baseUrl = Cypress.env( 'url' ).toString();

    // Деавторизація
    cy.visit( baseUrl + 'logout.php' );

    // Очистка cookies
    cy.clearCookies();

    // Очистка LocalStorage
    cy.clearLocalStorage();
};

export default {
    utilsUser
};