<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHandler
{
    private static $secret = "hello hello hello";

    public static function encode($payload, $expMinutes = 60)
    {
        $payload["exp"] = time() + ($expMinutes * 60);
        return JWT::encode($payload, self::$secret, "HS256");
    }

    public static function decode($token)
    {
        return JWT::decode($token, new Key(self::$secret, "HS256"));
    }
}