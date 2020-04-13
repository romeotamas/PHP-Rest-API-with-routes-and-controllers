<?php 
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

    global $db, $api, $route;

    require_once('./globals.php');
    require_once('./autoload.php');
    require_once('./includes/routes/Routes.php');

    $api = new Api();
    $api->run();
