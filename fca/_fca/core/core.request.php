<?php
//////////////////////////////////////////////////
// request
//////////////////////////////////////////////////

class FRequest{
    private static $_is_set = false;
    private static $php_file_path;
    private static $args = [];

    public static function http_method_name(){
        return fstr($_SERVER["REQUEST_METHOD"])->lower()->val();
    }

    public static function is_get(){
        return self::http_method_name() == "get";
    }

    public static function is_post(){
        return self::http_method_name() == "post";
    }

    public static function is_put(){
        return self::http_method_name() == "put";
    }

    public static function is_delete(){
        return self::http_method_name() == "delete";
    }

    public static function is_json(){
        return in_array('application/json',explode(';',$_SERVER['CONTENT_TYPE']));
    }

    public static function full_url(){
        $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return $full_url;
    }

    public static function url_path(){
        $full_url = self::full_url();
        $request_path = parse_url($full_url, PHP_URL_PATH);
        return $request_path;
    }

    public static function init_path(){
        if (self::$_is_set){
            return;
        }

        self::$_is_set = true;

        $request_path = self::url_path();        
        if ($request_path === "" || $request_path === "/"){
            $php_file_path = API_ROOT_DIR . "/index";

            if (file_exists($php_file_path . ".php")){
                self::$php_file_path = $php_file_path;
                return;
            }else{
                FResponse::_404_not_found();
            }
        }

        $request_path_list = explode("/", $request_path);
        $request_path_list = array_filter($request_path_list, function($item){
            return fstr($item)->strip() != "";
        });

        for ($i = count($request_path_list) + 1; $i >= 0;$i--){
            $sub_path = array_slice($request_path_list, 0, $i);            
            $php_path = API_ROOT_DIR . "/" . implode("/", $sub_path);
            

            if (file_exists($php_path . ".php")){                
                self::$php_file_path = $php_path;
                self::$args = array_slice($request_path_list, $i);
                return;
            }
        }

        // nothing.. res_404
    }

    public static function php_file_path(){
        return self::$php_file_path;
    }

    private static function _param_parser($param_key){
        $data_key = $param_key;        
        $field_name = null;
        if (fstr($param_key)->contains(":") === false){            
            return [$param_key, ffield(), null];
        }else{
            $data_key = fstr($param_key)->split(":", 0)->strip()->val();
            $field_name = fstr($param_key)->split(":", 1)->strip()->val();
            $alias = fstr($param_key)->split(":", 2);
            if ($alias != null){
                $alias = $alias->strip()->val();
            }

            if ($field_name === ""){
                return [$param_key, ffield(), $alias];
            }

            $user_fields = $GLOBALS["user_fields"];
            if (array_key_exists($field_name, $user_fields) === false){
                return [$param_key, ffield(), $alias];
            }

            $ffield = $user_fields[$field_name];
            return [$data_key, $ffield, $alias];
        }
    }

    public static function path_list(){
        self::init_path();        
        return self::$args;
    }

    public static function path($index, $default=null){
        list($param_key, $field, $alias) = self::_param_parser($index);
        if (count(self::$args) > $param_key){
            $param_value = self::$args[$param_key];
            if ($field->is_valid($param_value)){
                return $param_value;
            }
        }

        return $default;
    }

    public static function path_or_400($index, $error_message='path variable is invalid'){
        $path = self::path($index);
        if ($path == null){
            FResponse::_400_bad_request(["message" => $error_message]);
        }

        return $path;
    }
    
    public static function query($key, $default=null){
        list($param_key, $field, $alias) = self::_param_parser($key);
        if (isset($_GET[$param_key])){
            $param_value = $_GET[$param_key];
            if ($field->is_valid($param_value)){
                return $param_value;
            }
        }

        return $default;
    }

    public static function form($key, $default=null){
        list($param_key, $field, $alias) = self::_param_parser($key);
        if (isset($_POST[$param_key])){
            $param_value = $_POST[$param_key];
            if ($field->is_valid($param_value)){
                return $param_value;
            }
        }

        return $default;
    }

    public static function json($key, $default=null){
        list($param_key, $field, $alias) = self::_param_parser($key);

        $raw_body = file_get_contents("php://input");
        $raw_json = json_decode($raw_body, true);
        
        $arr = farray($raw_json);
        $param_value =  $arr->find($param_key, $default);

        if ($field->is_valid($param_value)){
            return $param_value;
        }
        return $default;
    }

    public static function param(...$keys){
        $ret = [];
        foreach($keys as $key){
            list($param_key, $field, $alias) = self::_param_parser($key);
            $bind_key = $param_key;
            if ($alias !== null){
                $bind_key = $alias;
            }
            if (ffield()->valid_int($param_key)){                
                $ret[$bind_key] = self::path($key);
            }
            elseif(self::is_get()){
                $ret[$bind_key] = self::query($key);
            }
            elseif (self::is_json()){
                $ret[$bind_key] = self::json($key);

                if ($ret[$bind_key] === null){
                    $ret[$bind_key] = self::query($key);
                }
            }
            elseif (self::is_post()){
                $ret[$bind_key] = self::form($key);

                if ($ret[$bind_key] === null){
                    $ret[$bind_key] = self::query($key);
                }
            }
            else{
                $ret[$bind_key] = null;
            }            
        }

        return $ret;
        
    }

    public static function param_or_400(...$keys){
        $param = self::param(...$keys);
        foreach($param as $v){
            if ($v === null){
                FResponse::_400_bad_request();
            }
        }

        return $param;
    }
}