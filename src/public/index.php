<?php
require __DIR__ . "/../app/config/db_connect.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// echo var_dump($uri);
if($uri[1] !== 'recipes') {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit();
}