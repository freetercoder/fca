<?php
//////////////////////////////////////////////////
// database
//////////////////////////////////////////////////

class FDB{
    private static function _pdo(){        
        $host = DB_HOST;
        $port = DB_PORT;
        $dbname=DB_NAME;
        $charset=DB_CHARSET;
        $username = DB_USER;
        $db_pw = DB_PASSWORD;
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";    
        $pdo = new PDO($dsn, $username, $db_pw);        
        return $pdo;
    }
    
    public static function query_all($query, $params=[], $pdo=null){
        if ($pdo == null){
            $pdo = self::_pdo();
        }
        try{
            $st = $pdo->prepare($query);
            $result = $st->execute($params);
    
            $fetch_result = $st->fetchAll(PDO::FETCH_ASSOC);
            return $fetch_result;
        }catch(Exception $e){
            return [];
        }
        finally{
            $pdo = null;
        }
    }
    
    public static function query_first($query, $params=[], $pdo=null){
        $list_result = self::query_all($query, $params, $pdo);
        if (count($list_result) == 0){
            return null;
        }
    
        return $list_result[0];
    }
    
    public static function query_last($query, $params=[], $pdo=null){
        $list_result = self::query_all($query, $params, $pdo);
        if (count($list_result) == 0){
            return null;
        }
    
        return $list_result[count($list_result) - 1];
    }

    public static function query_insert($query, $params=[], $pdo=null){
        try{
            if ($pdo == null){
                $pdo = self::_pdo();
            }
            $st = $pdo->prepare($query);
            $result = $st->execute($params);

            if ($result){
                return $pdo->lastInsertId();
            }else{
                return false;
            }
            
        }catch(Exception $e){        
            return false;
        }
        finally{
            $pdo = null;
        }
    }

    public static function query_execute($query, $params=[], $pdo=null){
        try{
            if ($pdo == null){
                $pdo = self::_pdo();
            }
            $st = $pdo->prepare($query);
            $result = $st->execute($params);

            if ($result){
                return true;
            }else{
                return false;
            }
            
        }catch(Exception $e){        
            var_dump($e);
            return false;
        }
        finally{
            $pdo = null;
        }
    }

    public static function first($table, $column, $value){
        $query = "select * from $table where $column = :value";
        return self::query_first($query, ["value" => $value]);
    }

    public static function first_or_401($table, $column, $value){
        $row = self::first($table, $column, $value);
        if ($row === null){
            FResponse::_401_not_authorized();
        }

        return $row;
    }

    public static function first_or_404($table, $column, $value){
        $row = self::first($table, $column, $value);
        if ($row === null){
            FResponse::_404_not_found();
        }

        return $row;
    }

    public static function insert($table, $data){
        $columns = array_keys($data);
        $value_placeholders = array_map(
            function($key){
                return ":$key";
            },
            $columns);

        $strColumn = implode(",",$columns);
        $strValuePlaceHolders = implode(",",$value_placeholders);

        $query = "insert into $table ($strColumn) values ($strValuePlaceHolders)";

        $last_insert_id = self::query_insert($query, $data);
        if (!$last_insert_id){
            return null;
        }

        return $last_insert_id;
    }

    public static function insert_and_return_first($table, $data){
        $last_insert_id = self::insert($table, $data);
        return self::first($table, "id", $last_insert_id);
    }

    public static function update($table, $data){
        $columns = array_keys($data);
        $placeholders = array_map(
            function($key){
                return "$key = :$key";
            },
            $columns);

        $strPlaceHolders = implode(",",$placeholders);

        $query = "update $table set $strPlaceHolders where id = :id";

        $query_result = self::query_execute($query, $data);    
        if (!$query_result){
            return false;
        }
        return true;
    }

    public static function update_and_return_first($table, $data){
        $upd_result = self::update($table, $data);
        if ($upd_result){
            return self::first($table, "id", $data["id"]);
        }
        return false;
    }

    public static function delete($table, $column, $value){
        $query = "delete from $table where $column = :value";
        return self::query_execute($query, ["value" => $value]);
    }
}

