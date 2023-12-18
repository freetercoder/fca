<?php
class FField{
    private $_rules = [
        "required" => false,
        "type" => "str",
        "min" => null,
        "max" => null ,
        "len_min" => null,
        "len_max" => null,
    ];

    private $_invalid_rule = null;


    public function required(){
        $this->_rules["required"] = true;
        return $this;
    }

    public function type_of_int(){
        $this->_rules["type"] = "int";
        return $this;
    }

    public function type_of_number(){
        $this->_rules["type"] = "number";
        return $this;
    }

    public function type_of_date(){
        $this->_rules["type"] == "date";
        return $this;
    }

    public function min($val){
        $this->_rules["min"] = $val;
        return $this;
    }

    public function max($val){
        $this->_rules["max"] = $val;
        return $this;
    }

    public function range($min, $max){
        $this->_rules["min"] = $min;
        $this->_rules["max"] = $max;
    }

    public function len_min($val){
        $this->_rules["len_min"] = $val;
        return $this;
    }

    public function len_max($val){
        $this->_rules["len_max"] = $val;
        return $this;
    }

    public function len_range($min, $max){
        $this->_rules["len_min"] = $min;
        $this->_rules["len_max"] = $max;
        return $this;
    }

    private function _set_invalid_rule($valid_result, $_invalid_rule){
        if ($valid_result === false){
            $this->_invalid_rule = "type_of_int";
        }
    }


    public function get_invalid_rule(){
        return $this->_invalid_rule;
    }

    public function is_valid($input){
        $is_required = $this->_rules["required"];
        if ($is_required){
            if ($this->valid_required($input) === false){
                return false;
            }
        }
        
        // required가 아니고, null 이면 true

        if ($is_required === false && $input === null){
            return true;
        }
        
        // required 일 때는 무조건 다 검사
        // required가 아닐 때는 null 이 아닐 때만 검사.
        //if ($is_required || ($is_required == false && $input !== null)){            
        $valid_result = true;        

        if ($valid_result && $this->_rules["type"] === "int"){
            $valid_result = $this->valid_int($input);
            $this->_set_invalid_rule($valid_result, "type_of_int");
        }

        if ($valid_result && $this->_rules["type"] === "number"){
            $valid_result = $this->valid_number($input);
            $this->_set_invalid_rule($valid_result, "type_of_number");
        }

        if ($valid_result && $this->_rules["type"] === "date"){
            $valid_result = $this->valid_date($input);
            $this->_set_invalid_rule($valid_result, "type_of_date");
        }

        if ($valid_result && $this->_rules["min"] !== null){
            $valid_result = $this->valid_number_min($input, $this->_rules["min"]);
            $this->_set_invalid_rule($valid_result, "min : " . $this->_rules["min"]);
        }

        if ($valid_result && $this->_rules["max"] !== null){
            $valid_result = $this->valid_number_max($input, $this->_rules["max"]);
            $this->_set_invalid_rule($valid_result, "max : " . $this->_rules["max"]);
        }

        if ($valid_result && $this->_rules["len_min"] !== null){
            $valid_result = $this->valid_str_len_min($input, $this->_rules["len_min"]);
            $this->_set_invalid_rule($valid_result, "len_min : " . $this->_rules["len_min"]);
        }

        if ($valid_result && $this->_rules["len_max"] !== null){
            $valid_result = $this->valid_str_len_max($input, $this->_rules["len_max"]);
            $this->_set_invalid_rule($valid_result, "len_max : " . $this->_rules["len_max"]);
        }

        return $valid_result;
    }

    public function valid_required($input){    
        return is_array($input) ? empty($input) === False : fstr($input)->strip()->val() !== '';
    }

    public function valid_int($input){
        return ctype_digit(strval($input));
    }

    public function valid_number($input){        
        return (bool) preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $input);
    }

    public function valid_date($input){
        return (bool) date_parse($input);
    }

    public function valid_number_min($input, $min){    
        if ($this->valid_number($input) === false){
            return false;
        }    
        
        if ($this->valid_number($min) === false){
            return false;
        }
    
        return $input >= $min;
    }

    public function valid_number_max($input, $min){    
        if ($this->valid_number($input) === false){
            return false;
        }    
        
        if ($this->valid_number($min) === false){
            return false;
        }   
    
        return $input <= $min;
    }

    public function valid_str_len_min($input, $len){
        if ($this->valid_number($len) === false){
            return false;
        }
        
        return ($len <= mb_strlen($input));
    }

    public function valid_str_len_max($input, $len){
        if ($this->valid_number($len) === false){
            return false;
        }
        
        return ($len >= mb_strlen($input));
    }

    
    
}

function ffield(){
    return new FField();
}