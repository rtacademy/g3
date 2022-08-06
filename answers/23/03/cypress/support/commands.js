Cypress.Commands.add( 'login', () =>
{
    const baseUrl = Cypress.env( 'url' ).toString();
    const { login, password, otp } = Cypress.env( 'instagram' );

    cy.session( [ login, password, otp ], () =>
    {
        cy.visit( baseUrl );

        cy.get( '#loginForm input[name=username]' )
            .should( 'be.visible' )
            .focus()
            .type( login, { delay: 10 } )
            .should( 'have.value', login );

        cy.get( '#loginForm input[name=password]' )
            .should( 'be.visible' )
            .focus()
            .type( password, { delay: 10 } )
            .should( 'have.value', password );

        cy.get( '#loginForm button[type=submit]' )
            .should( 'be.visible' )
            // ?? .should( 'be.not.disabled' ) // TODO
            .focus()
            .click();


    } );
} );