<?php
class FResponse{
    public static function _200_json($response_data){
        header("Content-Type: application/json");

        $json = json_encode($response_data);
        if ($json === false) {
            self::_500_internal_error();
        }

        http_response_code(200);
        echo $json;
        exit();
    }

    public static function _400_bad_request($response_data=null){
        header("Content-Type: application/json");
        http_response_code(400);

        $json = json_encode($response_data);
        if ($json) {
            echo $json;
        }
        
        exit();        
    }

    public static function _401_not_authorized(){
        header("Content-Type: application/json");
        http_response_code(401);
        exit();
    }

    public static function _404_not_found(){
        header("Content-Type: application/json");
        http_response_code(404);
        exit();
    }

    public static function _500_internal_error(){
        header("Content-Type: application/json");
        http_response_code(500);
        exit();
    }
}