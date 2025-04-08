<?php
class RecipeModel {
    private $pdo = null;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function find_one($uid) {
        $statement = "SELECT * from recipes WHERE uid = :uid;";
        try {
            $statement = $this->pdo->prepare($statement);
            $statement->execute(array(
                "uid" => (int)$uid
            ));
            $result = $statement->fetchAll();
            return (array) $result;
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    public function find_all() {
        $statement = "SELECT * from recipes;";
        try {
            $statement = $this->pdo->prepare($statement);
            $statement->execute();
            $result = $statement->fetchAll();
            return (array) $result;
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    public function insert($input) {
        $statement = "INSERT INTO recipes (name, prep_time, difficulty, veg) VALUES (:name, :prep_time, :difficulty, :veg);";
        try {
            $statement = $this->pdo->prepare($statement);
            $statement = $statement->execute(array(
                "name" => (string) $input["name"],
                "prep_time" => (int) $input["prep_time"],
                "difficulty" => (int) $input["difficulty"],
                "veg" => (bool) $input["veg"]
            ));
            // $result = $statement->rowCount();
            return array();
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    // public function update($uid, $data) {
    //     $statement = array(
    //         "name" => "UPDATE recipes SET name = :name WHERE uid = :uid",
    //         "prep_time" => "UPDATE recipes SET prep_time = :prep_time WHERE uid = :uid",
    //         "difficulty" => "UPDATE recipes SET difficulty = :difficulty WHERE uid = :uid",
    //         "veg" => "UPDATE recipes SET veg = :veg WHERE uid = :uid"
    //     );
    // }
}