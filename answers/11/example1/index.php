<?php

$page = $_GET['page'] ?? 'index';

$file_path = './pages/'. $page . '.php';

if( !file_exists( $file_path ) )
{
    $file_path = './pages/404.php';
}

require_once( $file_path );
require_once( './layout.php' );
