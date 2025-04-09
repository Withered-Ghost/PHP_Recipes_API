<?php
class RecipeModel
{
    private $pdo = null;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function find_one($uid)
    {
        $statement = "SELECT * from recipes WHERE uid = :uid;";
        try {
            $prepared = $this->pdo->prepare($statement);
            $executed = $prepared->execute(array(
                "uid" => (int) $uid
            ));
            $result = $prepared->fetchAll();
            return (array) $result;
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    public function find_all()
    {
        $statement = "SELECT * from recipes;";
        try {
            $prepared = $this->pdo->prepare($statement);
            $executed = $prepared->execute();
            $result = $prepared->fetchAll();
            return (array) $result;
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    public function insert($input)
    {
        $statement = "INSERT INTO recipes (name, prep_time, difficulty, veg) VALUES (:name, :prep_time, :difficulty, :veg);";
        try {
            $prepared = $this->pdo->prepare($statement);
            $executed = $prepared->execute(array(
                "name" => (string) $input["name"],
                "prep_time" => (int) $input["prep_time"],
                "difficulty" => (int) $input["difficulty"],
                "veg" => ($input["veg"] === true ? 1 : 0)
            ));
            $result = $prepared->rowCount();
            return array($result);
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    public function update($input)
    {
        $statement = "UPDATE recipes SET name = :name, prep_time = :prep_time, difficulty = :difficulty, veg = :veg WHERE uid = :uid;";
        try {
            $prepared = $this->pdo->prepare($statement);
            $executed = $prepared->execute(array(
                "name" => (string) $input["name"],
                "prep_time" => (int) $input["prep_time"],
                "difficulty" => (int) $input["difficulty"],
                "veg" => ($input["veg"] === true ? 1 : 0),
                "uid" => (int) $input["uid"]
            ));
            $result = $prepared->rowCount();
            return array($result);
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    public function rate($uid, $rating)
    {
        $statement_1 = "SELECT rating, rating_count from recipes WHERE uid = :uid;";
        $statement_2 = "UPDATE recipes SET rating = :rating, rating_count = :rating_count WHERE uid = :uid;";
        try {
            $prepared = $this->pdo->prepare($statement_1);
            $executed = $prepared->execute(array(
                "uid" => (int) $uid
            ));
            if ($prepared->rowCount() > 0) {
                $result = ((array) $prepared->fetchAll())[0];
                $new_rating = (float) $result["rating"];
                $rating_count = (int) $result["rating_count"];
                $new_rating = ((float) ($new_rating * $rating_count) + $rating) / (float) ($rating_count + 1);
                $rating_count = $rating_count + 1;

                $prepared = $this->pdo->prepare($statement_2);
                $executed = $prepared->execute(array(
                    "uid" => (int) $uid,
                    "rating" => (float) $new_rating,
                    "rating_count" => (int) $rating_count
                ));
                $result = $prepared->rowCount();
                return array($result);
            }
            $result = $prepared->rowCount();
            return array($result);
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }
}