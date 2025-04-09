<?php
require __DIR__ . "/../app/controller/RecipeController.php";
require __DIR__ . "/../app/config/DatabaseConnector.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
    if (stripos($_SERVER["CONTENT_TYPE"] ?? '', 'application/json') === false) {
        http_response_code(400);
        echo json_encode(["error" => "Only application/json content type is accepted."]);
        exit();
    }
}

$pdo = (new DatabaseConnector())->get_connector();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ($uri[1] === "recipes") {
    $uid = $uri[2] ?? null;
    $rating = $uri[3] ?? null;

    $recipe_controller = new RecipeController($uid, $rating, $pdo);
    $recipe_controller->process_request();
    exit();
}

http_response_code(404);
echo json_encode(array(
    "status" => 404,
    "message" => "Not Found"
));