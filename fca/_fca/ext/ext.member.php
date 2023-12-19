<?php
class FMember{
    public static function exist_or_401(){
        $member_token = FAuth::bearer_or_401();    
        $member = FDB::first_or_401("member", "member_token", $member_token);
        return $member;
    }

    public static function owner_or_400($field, $error_message="NOT_OWNER"){
        $member = self::exist_or_401();
        if ($field !== $member["id"]){
            FResponse::_400_bad_request($error_message);
        }
    }
}
