<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
require_once 'vendor/autoload.php';
require_once 'app/controllers/controller.php' ;
require_once 'app/config/config.php';

$url = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER[ 'REQUEST_URI' ] : null;
$action = explode( '/', $url )[1];
$Controller = new Controller();
switch ( $action ) {
    case 'send-prompt':
    $Controller->sendPrompt();
    break;

    case 'clear':
    $Controller->clearSession();
    break;

    default:
    $Controller->index();
}