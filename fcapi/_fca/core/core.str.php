<?php
//////////////////////////////////////////////////
// string
//////////////////////////////////////////////////
class FStr{
    private $str;
    public function __construct($str)
    {
        $this->str = $str;
    }

    public function __toString()
    {
        if ($this->str === null){
            return "";
        }
        return $this->str;
    }

    public function val(){
        return $this->str;
    }

    public function is_exist(){
        return $this->str !== null;
    }

    public function is_not_exist(){
        return $this->str === null;
    }

    public function is_empty(){
        return $this->is_not_exist() || $this->strip() == "";
    }

    public function is_not_empty(){
        return !$this->is_empty();
    }

    public function starts_with($value) {        
        return $value === "" || mb_strrpos($this->str, $value, -mb_strlen($this->str)) !== false;
    }

    public function ends_with($value){        
        return $value === "" || (($temp = mb_strlen($this->str) - mb_strlen($value)) >= 0 && mb_strpos($this->str, $value, $temp) !== false);
    }

    public function contains($value){
        return $value !== '' && mb_strpos($this->str, $value) !== false;
    }

    public function strip(){
        /*
        $trim_chars = '\s';
        $this->str = preg_replace('/^['.$trim_chars.']*(?U)(.*)['.$trim_chars.']*$/u', '\\1',$this->str);
        return $this;
        */
        $this->str = trim($this->str);
        return $this;
    }

    public function split($seperater, $index=null, $default_if_not_index=null){
        $ret = explode($seperater, $this->str);
        if ($index === null){
            return $ret;
        }
        if (count($ret) > $index){
            $this->str = $ret[$index];            
            return $this;
        }

        return $default_if_not_index;
    }

    public function split_lines(){
        return $this->split(PHP_EOL);
    }

    public function replace($old_str, $new_str){
        $this->str = str_replace($old_str, $new_str, $this->str);
        return $this;
    }

    public function lower(){
        $this->str = strtolower($this->str);
        return $this;
    }

    public function upper(){
        $this->str = strtoupper($this->str);
        return $this;
    }

    public function slice($start_index, $end_index = null){
        if ($end_index == null){
            $end_index = mb_strlen($this->str);
        }
        elseif ($end_index < 0){
            $end_index = mb_strlen($this->str) + $end_index;
        }
        elseif ($end_index > mb_strlen($this->str)){
            $end_index = mb_strlen($this->str);
        }

        $this->str = mb_substr($this->str, $start_index, $end_index);
        return $this;
    }

    public function as_bool($default=false){        
        if ($this->is_not_exist()){
            return $default;
        }

        return (bool) $this->str;
    }

    public function is_int(){
        return filter_var($this->str, FILTER_VALIDATE_INT);
    }
}

function fstr($str){
    return new FStr($str);
}