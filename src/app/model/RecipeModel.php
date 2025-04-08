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
            return $result;
        } catch (PDOException$e) {
            return array("error" => $e->getMessage());
        }
    }

    public function find_all() {
        $statement = "SELECT * from recipes;";
        try {
            $statement = $this->pdo->prepare($statement);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        } catch (PDOException$e) {
            return array("error" => $e->getMessage());
        }
    }
}