<?php
require_once __DIR__ . "/../config/JWTHandler.php";

function require_jwt_auth()
{
    $headers = getallheaders();

    if (!isset($headers["Authorization"])) {
        return 401;
    }

    $authHeader = $headers["Authorization"];
    if (!preg_match("/Bearer\s(\S+)/", $authHeader, $matches)) {
        return 401;
    }

    $token = $matches[1];

    try {
        $payload = (array) JWTHandler::decode($token);
        if (!isset($payload["exp"]) || $payload["exp"] < time() || !isset($payload["root"]) || $payload["root"] !== 1) {
            return 403;
        }
        return 200;
    } catch (Exception $e) {
        return 403;
    }
}