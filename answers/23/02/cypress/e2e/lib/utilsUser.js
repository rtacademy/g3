import { utilsResponse } from "./utilsResponse";

const utilsUser = {};

const baseUrl           = Cypress.env( 'url' ).toString();
const timeoutDefault    = parseInt( Cypress.env( 'timeouts' ).default );

utilsUser.checkAuthCookie = () =>
{
    const cookieName = Cypress.env( 'cookieName' ).toString();

    cy.getCookie( cookieName )
        .should( 'exist' )
        .should( 'have.property', 'value' );
};

utilsUser.authorize = ( userAuthorizeData ) =>
{
    const { login, password, firstname } = userAuthorizeData;

    cy.session( [ login, password ], () =>
    {
        cy.visit(
            baseUrl,
            {
                timeout: timeoutDefault
            }
        );

        cy.intercept( 'GET', '**/login.php' )
            .as( 'loginPage' );

        cy.get( 'footer .nav #user-area-login' )
            .should( 'be.visible' )
            .click();

        let pageLoadStart = new Date().getTime();

        cy.wait( '@loginPage' )
            .should( ( { response } ) =>
                utilsResponse.checkTimeoutContentTypeStatus(
                    response, pageLoadStart, timeoutDefault
                )
            );

        cy.intercept( 'POST', '**/login.php' )
            .as( 'loginPagePost' );

        cy.get( '#form-user-login' )
            .should( 'be.visible' )
            .focus()
            .type( login, { delay: 10 } )
            .should( 'have.value', login );

        cy.get( '#form-user-password' )
            .should( 'be.visible' )
            .focus()
            .type( password, { delay: 10 } )
            .should( 'have.value', password );

        cy.get( '#form-user-submit' )
            .should( 'be.visible' )
            // ?? .should( 'be.not.disabled' ) // TODO
            .focus()
            .click();

        pageLoadStart = new Date().getTime();

        cy.wait( '@loginPagePost' )
            .should( ( { response } ) =>
                utilsResponse.checkTimeoutContentTypeStatus(
                    response, pageLoadStart, timeoutDefault, 302
                )
            );

        cy.get( 'footer .nav ul li:first-child' )
            .should( 'contain', firstname );

        // перевірка існування авторизаційної кукі
        utilsUser.checkAuthCookie();
    } );
};

utilsUser.deAuthorize = () =>
{
    // Деавторизація
    cy.visit(
        baseUrl + 'logout.php',
        {
            timeout: timeoutDefault
        }
    );

    // Очистка cookies
    cy.clearCookies();

    // Очистка LocalStorage
    cy.clearLocalStorage();
};

export default {
    utilsUser
};