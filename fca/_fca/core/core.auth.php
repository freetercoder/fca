<?php
class FAuth{
    public static function headers(){
        return getallheaders();
    }

    public static function bearer($default=null){
        $headers = self::headers();
        if (array_key_exists("Authorization", $headers)){
            $value = $headers["Authorization"];
            if (fstr($value)->strip()->lower()->starts_with("bearer")){
                $ret = fstr($value)->strip()->split(" ");
                if (count($ret) <= 1){
                    return $default;
                }

                $ret = farray($ret)->slice(1)->join(" ");
                return $ret;
            }else{
                return $default;
            }
        }
    }

    public static function bearer_or_401(){
        $bearer = self::bearer();
        if ($bearer === null){
            FResponse::_401_not_authorized();
        }

        return $bearer;
    }

    public static function bearer_member_or_401(){
        $member_token = FAuth::bearer_or_401();    
        $member = FDB::first_or_401("member", "member_token", $member_token);
        return $member;
    }

    public static function bearer_member_owner_or_400($field, $error_message="NOT_OWNER"){
        $member = self::bearer_member_or_401();
        if ($field !== $member["id"]){
            FResponse::_400_bad_request($error_message);
        }

        return $member;
    }
}