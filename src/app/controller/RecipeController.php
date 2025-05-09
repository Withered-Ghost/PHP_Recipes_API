<?php
require_once __DIR__ . "/../model/RecipeModel.php";
require_once __DIR__ . "/../view/ResponseView.php";
require __DIR__ . "/../middleware/jwt_auth.php";

class RecipeController
{
    private $pdo = null;
    private $uid = null;
    private $rating = null;
    private $recipe_obj = null;

    public function __construct($uid, $rating, $pdo)
    {
        $this->pdo = $pdo;
        $this->recipe_obj = new RecipeModel($this->pdo);
        $this->uid = (int) $uid;
        $this->rating = (int) $rating;
    }

    public function process_request()
    {
        $response = null;
        $auth = null;

        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                if ($this->uid && !$this->rating) {
                    // get recipe uid
                    $response = $this->get_one_recipe($this->uid);
                } else if (!$this->uid && !$this->rating) {
                    // get all recipes
                    $response = $this->get_all_recipes();
                } else {
                    $response = ResponseView::$msg_arr[404];
                }
                break;

            case "POST":
                if ($this->uid && $this->rating) {
                    // update rating
                    $response = $this->rate_recipes($this->uid, $this->rating);
                } else if (!$this->uid && !$this->rating) {
                    // create recipe
                    $auth = require_jwt_auth();
                    if ($auth !== 200) {
                        $response = ResponseView::$msg_arr[$auth];
                        break;
                    }
                    $response = $this->create_recipe();
                } else {
                    $response = ResponseView::$msg_arr[404];
                }
                break;

            case "PUT":
                if ($this->uid && !$this->rating) {
                    // update recipe
                    $auth = require_jwt_auth();
                    if ($auth !== 200) {
                        $response = ResponseView::$msg_arr[$auth];
                        break;
                    }
                    $response = $this->update_recipe($this->uid);
                } else {
                    $response = ResponseView::$msg_arr[404];
                }
                break;

            case "DELETE":
                if ($this->uid && !$this->rating) {
                    // delete recipe
                    $auth = require_jwt_auth();
                    if ($auth !== 200) {
                        $response = ResponseView::$msg_arr[$auth];
                        break;
                    }
                    $response = $this->delete_recipe($this->uid);
                } else {
                    $response = ResponseView::$msg_arr[404];
                }
                break;

            default:
                $response = ResponseView::$msg_arr[404];
                break;
        }

        http_response_code($response["status"]);
        echo json_encode($response);
    }

    private function get_one_recipe($uid)
    {
        if (!is_int($uid) || $uid < 1) {
            return ResponseView::$msg_arr[400];
        }
        $result = $this->recipe_obj->find_one($uid);
        if (isset($result["error"])) {
            return ResponseView::$msg_arr[500];
        }
        if (sizeof($result) === 0) {
            return ResponseView::$msg_arr[404];
        }
        $response = ResponseView::$msg_arr[200];
        $response["data"] = $result[0];
        return $response;
    }

    private function get_all_recipes()
    {
        $result = $this->recipe_obj->find_all();
        if (isset($result["error"])) {
            return ResponseView::$msg_arr[500];
        }
        $response = ResponseView::$msg_arr[200];
        $response["data"] = $result;
        return $response;
    }

    private function create_recipe()
    {
        $input = (array) json_decode(file_get_contents("php://input"), true);
        if (!$this->validate_recipe($input, false)) {
            return ResponseView::$msg_arr[400];
        }
        $result = $this->recipe_obj->insert($input);
        if (isset($result["error"])) {
            return ResponseView::$msg_arr[500];
        }
        $response = ResponseView::$msg_arr[201];
        $response["affected_rows"] = $result[0];
        return $response;
    }

    private function update_recipe($uid)
    {
        $input = (array) json_decode(file_get_contents("php://input"), true);
        $input["uid"] = $uid;
        if (!$this->validate_recipe($input, true)) {
            return ResponseView::$msg_arr[400];
        }
        $result = $this->recipe_obj->update($input);
        if (isset($result["error"])) {
            return ResponseView::$msg_arr[500];
        }
        $response = ResponseView::$msg_arr[200];
        $response["affected_rows"] = $result[0];
        return $response;
    }

    private function rate_recipes($uid, $rating)
    {
        if (!is_int($rating) || $rating < 1 || $rating > 5 || !is_int($uid) || $uid < 1) {
            return ResponseView::$msg_arr[400];
        }
        $result = $this->recipe_obj->rate($uid, $rating);
        if (isset($result["error"])) {
            return ResponseView::$msg_arr[500];
        }
        $response = ResponseView::$msg_arr[200];
        $response["affected_rows"] = $result[0];
        return $response;
    }

    private function delete_recipe($uid)
    {
        if (!is_int($uid) || $uid < 1) {
            return ResponseView::$msg_arr[400];
        }
        $result = $this->recipe_obj->delete($uid);
        if (isset($result["error"])) {
            return ResponseView::$msg_arr[500];
        }
        $response = ResponseView::$msg_arr[200];
        $response["affected_rows"] = $result[0];
        return $response;
    }

    private function validate_recipe($input, $validate_uid)
    {
        if (!isset($input["name"]) || !is_string($input["name"]) || !$input["name"]) {
            return false;
        }
        if (!isset($input["prep_time"]) || !is_int($input["prep_time"]) || $input["prep_time"] < 1) {
            return false;
        }
        if (!isset($input["difficulty"]) || !is_int($input["difficulty"]) || $input["difficulty"] < 1 || $input["difficulty"] > 3) {
            return false;
        }
        if (!array_key_exists("veg", $input) || !is_bool($input["veg"])) {
            return false;
        }
        if ($validate_uid && (!isset($input["uid"]) || !is_int($input["uid"]) || $input["uid"] < 1)) {
            return false;
        }
        return true;
    }
}