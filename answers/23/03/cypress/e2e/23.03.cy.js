describe( 'Instagram', () =>
{
    let baseUrl;

    before( () =>
    {
        baseUrl = Cypress.env( 'url' ).toString();
    } );

    beforeEach( () =>
    {
        // авторизація (або відновлення сесії)
        cy.login();

        cy.visit( baseUrl );
    } );

    it( 'Авторизація', () =>
    {

    } );
} )