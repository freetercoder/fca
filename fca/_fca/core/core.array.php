<?php
class FArray{
    private $_array;

    public function __construct($_array){
        $this->_array = $_array;
    }

    public function val(){
        return $this->_array;
    }

    public function find($key, $default=null){
        $key_split = explode(".", $key);
        $key_split = array_map(function($item){return fstr($item)->strip()->val();}, $key_split);

        $parent_dict = $this->_array;
        foreach($key_split as $key_idx => $current_key){
            if (array_key_exists($current_key, $parent_dict) === false){                
                return $default;
            }

            if ($key_idx == count($key_split) - 1){
                return $parent_dict[$current_key];
            }

            $parent_dict = $parent_dict[$current_key];
        }

        return $default;
    }

    public function slice($start=0, $end=null){
        if ($end == null){
            $end = count($this->_array);
        }

        $this->_array = array_slice($this->_array, $start, $end - $start);
        return $this;
    }

    public function join($glue=""){
        return implode($glue, $this->_array);
    }

    public function add($value){
        array_push($this->_array, $value);
        return $this;
    }
}

function farray($_array){
    return new FArray($_array);
}