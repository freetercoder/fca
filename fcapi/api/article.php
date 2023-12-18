<?php
if (defined("FCA") === false){exit();}
// table.php - article title content => article : id, title, content, member_id, visible_status, insert_dt, update_dt
function _get_list(){
    $query = "select id, title, content, member_id from article where 1 = 1 and visible_status = 'public' order by insert_dt asc";
    return FDB::query_all($query);
}

function _get_item(){
    $id = FRequest::path_or_400("0: id");
    $query = "select title, content, member_id from article where 1 = 1 and visible_status = 'public' and id = :id";
    return FDB::query_first($query, ["id" => $id]);
}

function get(){
    return FRequest::path(0) == null ? _get_list() : _get_item();
}

function post(){        
    $params = FRequest::param_or_400("title: article.title", "content: article.content");

    // check is login
    $member_token = FAuth::bearer_or_401();    
    $member = FDB::first_or_401("member", "member_token", $member_token);
    
    // **TODO :: check member_id field.**
    $params["member_id"] = $member["id"];
    $article = FDB::insert_and_return_first("article", $params);

    
    return $article;
}

function put(){    
    $params = FRequest::param_or_400("0: id : id", "title: article.title", "content: article.content");
    $member_token = FAuth::bearer_or_401();
    $member = FDB::first_or_401("member", "member_token", $member_token);

    $article = FDB::first_or_404("article", "id", $params["id"]);
    
    if ($article["member_id"] !== $member["id"]){
        FResponse::_400_bad_request("article only modify possible by member");
    }
    
    $article = FDB::update_and_return_first("article", $params);
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
    return FDB::delete("article", "id", $id);
}