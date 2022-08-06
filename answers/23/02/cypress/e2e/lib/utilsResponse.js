const utilsResponse = {};

utilsResponse.checkHttpStatus = ( response ) =>
{
    expect( response ).to.have.property( 'statusCode' );
    expect( response.statusCode ).to.eq( 200 );
    expect( response ).to.have.property( 'statusMessage' );
    expect( response.statusMessage ).to.eq( 'OK' );
};

utilsResponse.checkJSON = ( response ) =>
{
    utilsResponse.checkHttpStatus( response );

    expect( response.headers ).to.have.property( 'content-type' );
    expect( response.headers[ 'content-type' ] ).to.eq( 'application/json' );
};

utilsResponse.checkHTML = ( response ) =>
{
    utilsResponse.checkHttpStatus( response );

    expect( response.headers ).to.have.property( 'content-type' );
    expect( response.headers[ 'content-type' ] ).to.eq( 'text/html' );
};

export default {
    utilsResponse
};