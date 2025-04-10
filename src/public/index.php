<?php
require_once __DIR__ . "/../app/vendor/autoload.php";
require_once __DIR__ . "/../app/controller/LoginController.php";
require_once __DIR__ . "/../app/controller/RecipeController.php";
require_once __DIR__ . "/../app/config/DatabaseConnector.php";
require_once __DIR__ . "/../app/view/ResponseView.php";

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
    $uri_2 = $uri[2] ?? null;
    $uri_3 = $uri[3] ?? null;
    $uri_4 = $uri[4] ?? null;

    if ($uri_2 === "login" && !$uri_3) {
        // login and return JWT
        $login_controller = new LoginController($pdo);
        $login_controller->process_login();
        exit();
    } else if (!$uri_4) {
        $recipe_controller = new RecipeController($uri_2, $uri_3, $pdo);
        $recipe_controller->process_request();
        exit();
    }
}

http_response_code(404);
echo json_encode(ResponseView::$msg_arr[404]);
exit();