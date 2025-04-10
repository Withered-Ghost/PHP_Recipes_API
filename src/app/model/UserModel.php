<?php
class UserModel
{
    private $pdo = null;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function find_one($input)
    {
        $statement = "SELECT * from users WHERE email = :email AND password = :password;";
        try {
            $prepared = $this->pdo->prepare($statement);
            $executed = $prepared->execute(array(
                "email" => (string) $input["email"],
                "password" => (string) $input["password"]
            ));
            $result = $prepared->fetchAll();
            return (array) $result;
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }
}