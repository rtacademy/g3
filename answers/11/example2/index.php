<?php

use lib\TavriaCar;

spl_autoload_register(
    static function( string $className ) {
        require( str_replace( '\\', '/', $className ) . '.php' );
    }
);

try
{
    $tavria = new TavriaCar();
    var_dump( $tavria );
    var_dump( $tavria->getEngineVolume() );
    var_dump( TavriaCar::getName() );
}
catch( Throwable $e )
{
    die( '<div style="color:#990000;">' . $e->getMessage() . '</div>' );
}
