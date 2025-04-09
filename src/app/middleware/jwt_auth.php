<?php
require_once __DIR__ . "/../config/JWTHandler.php";

function require_jwt_auth()
{
    $headers = getallheaders();

    if (! isset($headers["Authorization"])) {
        http_response_code(401);
        echo json_encode(["error" => "Authorization header missing"]);
        exit();
    }

    $authHeader = $headers["Authorization"];
    if (! preg_match("/Bearer\s(\S+)/", $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(array(
            "status" => 401,
            "message" => "Unauthorized"
        ));
        exit();
    }

    $token = $matches[1];

    try {
        $decoded = JWTHandler::decode($token);
        return $decoded; // you can return user data if included in payload
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array(
            "status" => 401,
            "message" => "Unauthorized"
        ));
        exit();
    }
}
