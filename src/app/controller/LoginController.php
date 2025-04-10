<?php
require_once __DIR__ . "/../config/JWTHandler.php";
require_once __DIR__ . "/../model/UserModel.php";
require_once __DIR__ . "/../view/ResponseView.php";

class LoginController
{
    private $pdo = null;
    private $user_obj = null;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->user_obj = new UserModel($this->pdo);
    }

    public function process_login()
    {
        $response = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // login
            $response = $this->login();
        } else {
            $response = ResponseView::$msg_arr[404];
        }

        http_response_code($response["status"]);
        echo json_encode($response);
    }

    private function login()
    {
        $input = (array) json_decode(file_get_contents("php://input"), true);
        if (!isset($input["email"]) || !filter_var($input["email"], FILTER_VALIDATE_EMAIL) || !isset($input["password"]) || !is_string($input["password"])) {
            return ResponseView::$msg_arr[400];
        }
        $result = $this->user_obj->find_one($input);
        if (isset($result["error"])) {
            return ResponseView::$msg_arr[500];
        }
        if (sizeof($result) > 0) {
            // create JWT a/c to role
            $payload = array(
                "iat" => time(),
                "email" => $input["email"],
                "root" => $result[0]["root"]
            );
            $token = JWTHandler::encode($payload);
            $response = ResponseView::$msg_arr[200];
            $response["token"] = $token;
            return $response;
        } else {
            return ResponseView::$msg_arr[401];
        }
    }
}