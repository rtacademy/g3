const { defineConfig } = require( "cypress" );

module.exports = defineConfig( {
    watchForFileChanges: false,
    chromeWebSecurity  : false,

    e2e: {
        setupNodeEvents( on, config )
        {
        },
        experimentalSessionAndOrigin: true
    },
} );