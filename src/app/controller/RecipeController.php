<?php
require __DIR__ . "/../config/DatabaseConnector.php";
require __DIR__ . "/../model/RecipeModel.php";

class RecipeController {
    private $pdo = null;
    private $uid = null;
    private $rating = null;
    private $recipe_obj = null;
    private $res_arr = null;

    public function __construct($uid, $rating) {
        $this->pdo = (new DatabaseConnector())->get_connector();
        $this->recipe_obj = new RecipeModel($this->pdo);
        $this->uid = (int)$uid;
        $this->rating = (int)$rating;
        $this->res_arr = array(
            200 => array(
                "status" => 200,
                "message" => "OK"
            ),
            403 => array(
                "status" => 403,
                "message" => "Forbidden"
            ),
            404 => array(
                "status" => 404,
                "message" => "Not Found"
            ),
            500 => array(
                "status" => 500,
                "message" => "Internal Server Error"
            )
        );
    }

    public function process_request() {
        $response = null;

        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                if ($this->uid && ! $this->rating) {
                    // get recipe uid
                    $response = $this->get_one_recipe($this->uid);
                } else if (! $this->uid && ! $this->rating){
                    // get all recipes
                    $response = $this->get_all_recipes();
                } else {
                    $response = $this->res_arr[404];
                }
                break;

                case "POST":
                    if ($this->uid && $this->rating) {
                        // update rating
                        echo 3;
                    } else if (! $this->uid && ! $this->rating){
                        // create recipe
                        echo 4;
                    } else {
                        $response = $this->res_arr[404];
                    }
                    break;

                case "PUT":
                    if ($this->uid && ! $this->rating) {
                        // update recipe
                        echo 5;
                    } else {
                        $response = $this->res_arr[404];
                    }
                    break;

                case "DELETE":
                    if ($this->uid && ! $this->rating) {
                        // delete recipe
                        echo 6;
                    } else {
                        $response = $this->res_arr[404];
                    }
                    break;

                default:
                    $response = $this->res_arr[404];
                    break;
        }
        http_response_code($response["status"]);
        echo json_encode($response);
    }

    private function get_one_recipe($uid) {
        $result = $this->recipe_obj->find_one($uid);
        if (isset($result["error"])) {
            return $this->res_arr[500];
        }
        if(sizeof($result) == 0) {
            return $this->res_arr[404];
        }
        $response = $this->res_arr[200];
        $response["data"] = $result[0];
        return $response;
    }

    private function get_all_recipes() {
        $result = $this->recipe_obj->find_all();
        if (isset($result["error"])) {
            return $this->res_arr[500];
        }
        $response = $this->res_arr[200];
        $response["data"] = $result[0];
        return $response;
    }
}