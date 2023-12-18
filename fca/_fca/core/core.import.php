<?php
//////////////////////////////////////////////////
// import
//////////////////////////////////////////////////
class FImport{
    public static function php($path){
        $import_path = "";
        $is_relative = fstr($path)->starts_with("./") || fstr($path)->starts_with("../");
        
    
        if ($is_relative){
            $import_path = dirname(__FILE__) . "/" . $path . ".php";
        }else{ // absolute path
            $import_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $path . ".php";
        }
        
        if (file_exists($import_path)){            
            require_once($import_path);
        }
    }
    
    public static function api(){
        FRequest::init_path();
        $php_file_path = FRequest::php_file_path();
        
        if ($php_file_path == null){            
            FResponse::_404_not_found();
        }

        $method_name = FRequest::http_method_name();
        self::php($php_file_path);
        
        if (function_exists($method_name) === false){
            FResponse::_404_not_found();
        }

        $result = call_user_func($method_name);

        // header 설정
        FResponse::_200_json($result);
    }
}
