<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/core/core.str.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/core/core.array.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/core/core.db.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/core/core.import.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/core/core.request.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/core/core.field.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/user/user.config.php");

FImport::php("_fca/user/user.config");

function _get_column_query($col_name){
    $data_type = 'VARCHAR';
    $nullable = True;
    $default_value = null;
    $extra_value = "";
    $data_length = null;

    if ($col_name === 'id'){
        $data_type = 'INT UNSIGNED';
        $nullable = False;
        $extra_value = 'AUTO_INCREMENT';
    }

    if (fstr($col_name)->ends_with("_id")){
        $data_type = 'INT UNSIGNED';        
    }

    if (fstr($col_name)->ends_with('_dt')){
        $data_type = 'datetime';        
    }

    if ($col_name === "insert_dt"){
        $extra_value = 'DEFAULT CURRENT_TIMESTAMP';
        $nullable = False;
    }

    if ($col_name === "update_dt"){
        $extra_value = 'on update CURRENT_TIMESTAMP';
    }

    if (fstr($col_name)->ends_with("link") ||  fstr($col_name)->ends_with(("url"))){
        $data_type = "VARCHAR";
        $data_length = 2083;
    }

    if (fstr($col_name)->ends_with("_yn")){
        $data_type = "CHAR";
        $data_length = 1;
        $default_value = "'Y'";
        $nullable = False;
    }

    if (fstr($col_name)->ends_with("_type")){
        $data_type = "CHAR";
        $data_length = 10;
    }

    if (fstr($col_name)->ends_with("sort_order")){
        $data_type = 'INT UNSIGNED';
        $nullable = False;
        $default_value = "1";
    }

    if ($col_name === "visible_status"){
        $data_type = "CHAR";
        $data_length = 10;
        $default_value = "'public'";
    }

    if ($data_type === "VARCHAR" && $data_length === null){
        $data_length = 255;
    }
    
    $data_type = $data_length !== null ? "$data_type($data_length)" : $data_type;
    $nullable_str = $nullable ? "NULL" : "NOT NULL";
    $default_str = $default_value !== null ? "DEFAULT $default_value" : "";
    $column_query = "`$col_name` $data_type $nullable_str $default_str $extra_value";    
    return $column_query;
}

function gen_table($table_name, $columns){
    $create_query = "
CREATE TABLE `$table_name` 
( 
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,    
";

    if ($table_name !== "member"){
        array_push($columns, "member_id");
    }
    
    array_push($columns, "visible_status");
    array_push($columns, "insert_dt");
    array_push($columns, "update_dt");

    foreach($columns as $col_name){
        $create_query .= "    " . _get_column_query($col_name) . "," . PHP_EOL;
    }

    $create_query .= "
    PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;    
";

    return $create_query;
}



function gen_create_query($cmd){
    # $test_value = "article title content";
    $cmd = explode(" ", $cmd);
    $table_name = $cmd[0];
    $columns = farray($cmd)->slice(1)->val();
    
    return gen_table($table_name, $columns);
}

function create_table($cmd, $apply_db){
    // $cmd = "article2 title content sample";
    $create_query = gen_create_query($cmd);
    if($apply_db){
        FDB::query_execute($create_query);
    }
    return $create_query;
}

function create_member_insert($apply_db){
    $member_token = uniqid("", true);
    $query = "insert into member (member_token) values ('$member_token')";

    if ($apply_db){
        FDB::query_execute($query);
    }
    return $query;
}

function gen_api($cmd){
    $origin_cmd = $cmd;
    $cmd = explode(" ", $cmd);
    $table_name = $cmd[0];
    $columns = farray($cmd)->slice(1);

    $column_comma = $columns->join(", ");
    $column_param = farray([]);
    foreach($columns->val() as $column){
        $column_param->add("\"$column: $table_name.$column\"");
    }

    $column_param = $column_param->join(", ");

    $template_head = <<<'CDATA'
<?php
if (defined("FCA") === false){exit();}

CDATA;

    $template = <<<'CDATA'
// table.php - {$origin_cmd} => {$table_name} : id, {$column_comma}, member_id, visible_status, insert_dt, update_dt
function _get_list(){
    $query = "select id, {$column_comma}, member_id from {$table_name} where 1 = 1 and visible_status = 'public' order by insert_dt asc";
    return FDB::query_all($query);
}

function _get_item(){
    $id = FRequest::path_or_400("0: id");
    $query = "select {$column_comma}, member_id from {$table_name} where 1 = 1 and visible_status = 'public' and id = :id";
    return FDB::query_first($query, ["id" => $id]);
}

function get(){
    return FRequest::path(0) == null ? _get_list() : _get_item();
}

function post(){    
    $params = FRequest::param_or_400({$column_param});

    // check is login
    $member_token = FAuth::bearer_or_401();    
    $member = FDB::first_or_401("member", "member_token", $member_token);
    
    // **TODO :: check member_id field.**
    $params["member_id"] = $member["id"];
    $article = FDB::insert_and_return_first("{$table_name}", $params);
    return $article;
}

function put(){    
    $params = FRequest::param_or_400("0: id : id", {$column_param});
    $member_token = FAuth::bearer_or_401();
    $member = FDB::first_or_401("member", "member_token", $member_token);

    $article = FDB::first_or_404("{$table_name}", "id", $params["id"]);
    
    if ($article["member_id"] !== $member["id"]){
        FResponse::_400_bad_request("{$table_name} only modify possible by member");
    }
    
    $article = FDB::update_and_return_first("{$table_name}", $params);
    return $article;
}

function delete(){
    $id = FRequest::path_or_400("0: id");
    $member_token = FAuth::bearer_or_401();
    $member = FDB::first_or_401("member", "member_token", $member_token);

    $article = FDB::first_or_404("article", "id", $id);
    
    if ($article["member_id"] !== $member["id"]){
        FResponse::_400_bad_request("article only delete possible by member");
    }    
    return FDB::delete("{$table_name}", "id", $id);
}
CDATA;
    $template = fstr($template)
        ->replace('{$origin_cmd}', $origin_cmd)
        ->replace('{$table_name}', $table_name)
        ->replace('{$column_comma}', $column_comma)
        ->replace('{$column_param}', $column_param)
        ->val();
    return [$table_name, $template_head, $template];
}

function create_api($cmd, $apply_file){
    list($table_name, $template_head, $template) = gen_api($cmd);
    if ($apply_file){
        $api_dir = $_SERVER["DOCUMENT_ROOT"] . "/" . API_ROOT_DIR;
        if (file_exists($api_dir) === false){
            mkdir($api_dir);
        }
        $api_path = $api_dir . "/$table_name.php";
        if (file_exists($api_path) === false){
            file_put_contents($api_path, $template_head);
        }

        file_put_contents($api_path, $template, FILE_APPEND);
    }

    return $template_head . $template;
}

$cmd = "";
$radio_sql = "only_show";
$radio_api = "only_show";
$create_member_table = false;

$sql_result = "";
$file_result = "";
if (FRequest::is_post()){
    $cmd = FRequest::form("cmd");
    $radio_sql = FRequest::form("sql", "only_show");
    $apply_db = $radio_sql === "apply_db";
    $sql_result = create_table($cmd, $apply_db);

    $create_member_table = FRequest::form("create_member_table", false);
    if ($create_member_table === "Y"){
        $sql_result .= create_table("member member_token", $apply_db);

        $sql_result .= create_member_insert($apply_db);
    }

    $radio_api = FRequest::form("api", "only_show");
    $apply_file = $radio_api === "apply_file";

    $file_result = create_api($cmd, $apply_file);
}
?>
<html>
    <head>
        <title>FCA Create Table</title>
        <style>
            .width_100{
                width:100%;
            }
            .width_80{
                width:80%;
            }
            .margin_right_50px{
                margin-right: 50px;
            }
        </style>

    </head>
    <body>
        <form method="post" id="form">
            <h1>TABLE COMMAND</h1>
            <p>
                <input type='text' id='cmd' name="cmd"  class="width_80" value="<?= $cmd ?>" placeholder="command" />
                <input type="submit" value="generate" />
                <br />
                sample : article title content
            </p>
            <p>
                SQL Create Query GENERATE :                 
                ONLY SHOW <input type="radio" name="sql" value="only_show" class="margin_right_50px" <?= $radio_sql === "only_show" ? "checked" : "" ?> > 
                APPLY DB <input type="radio" name="sql" value="apply_db" class="margin_right_50px" <?= $radio_sql === "apply_db" ? "checked" : "" ?> > 
                <strong>create member table ? </strong><input type="checkbox" name="create_member_table" value="Y" <?= $create_member_table ? "checked" : "" ?> />
            </p>
            <p>
                Template API GENERATE :
                ONLY SHOW <input type="radio" name="api" value="only_show" class="margin_right_50px" <?= $radio_api === "only_show" ? "checked" : "" ?> > 
                APPLY FILE <input type="radio" name="api" value="apply_file" class="margin_right_50px" <?= $radio_api === "apply_file" ? "checked" : "" ?> > 
            </p>
            <hr />
            <h1>SQL RESULT</h1>
            <textarea id='sql_result' name="sql_result" class="width_100" rows="20"><?= $sql_result ?></textarea>
            <h1>FILE RESULT</h1>
            <textarea id='sql_result' name="sql_result" class="width_100" rows="20"><?= $file_result ?></textarea>
            
        </form>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#cmd").on("keyup", function(key){
                    if (key.keyCode == 13){                        
                        $("#form").submit();
                    }
                });
            });
        </script>
    </body>
</html>