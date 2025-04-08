<?php
require __DIR__ . "/env_loader.php";

function db_connect() {
    $hostname = getenv("db_hostname");
    $username = getenv("db_user");
    $password = getenv("db_password");
    $db_name = getenv("db_name");

    $dsn = "mysql:host=$hostname;dbname=$db_name";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    try {
        $pdo = new \PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection error: " . $e->getMessage());
    }
}