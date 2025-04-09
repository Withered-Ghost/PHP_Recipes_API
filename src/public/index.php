<?php
require __DIR__ . "/../app/controller/RecipeController.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ($uri[1] === "recipes") {
    $uid = null;
    $rating = null;
    if (sizeof($uri) > 2 && $uri[2]) {
        $uid = $uri[2];
    }
    if (sizeof($uri) > 3 && $uri[3]) {
        $rating = $uri[3];
    }
    // echo var_dump($uri);
    $recipe_controller = new RecipeController($uid, $rating);
    $recipe_controller->process_request();
    exit();
}

http_response_code(404);
echo json_encode(array(
    "status" => 404,
    "message" => "Not Found"
));