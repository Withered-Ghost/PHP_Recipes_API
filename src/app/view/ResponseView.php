<?php
class ResponseView
{
    public static $msg_arr = array(
        200 => array(
            "status" => 200,
            "message" => "OK"
        ),
        201 => array(
            "status" => 201,
            "message" => "Created"
        ),
        400 => array(
            "status" => 400,
            "message" => "Bad Request"
        ),
        401 => array(
            "status" => 401,
            "message" => "Unauthorized"
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