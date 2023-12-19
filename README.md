**FCA :: 가장 단순하고 쉽게 API를 구축하는 PHP 프레임워크.**

# 프로젝트 개요
FCA(**F**reeter **C**oder **A**PI)는 가장 단순하고 쉬운 방법으로 API를 구축할 수 있게 도와주는 PHP 프레임워크입니다.  
PHP와 MySQL만 설치되어 있다면, 5분 이내에 간단한 API 서버를 구축할 수 있습니다.  

FCA의 장점은 다음과 같습니다.

1. 복잡한 터미널이나 커맨드가 필요하지 않습니다. 모든 것은 웹에서 처리할 수 있습니다.
2. 간단한 명령어로 데이터베이스 테이블을 생성할 수 있습니다.
3. 데이터베이스 테이블을 기반으로 API 생성이 가능합니다.
4. MVC를 몰라도 됩니다. Vanilla PHP 방식으로 API 개발이 가능합니다.
5. Composer가 필요하지 않습니다.

# 샘플 코드
## 테이블 생성
### 입력
```sh
article title content
```
### 출력 결과
```SQL
CREATE TABLE `article` 
( 
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,    
    `title` VARCHAR(255) NULL  ,
    `content` VARCHAR(255) NULL  ,
    `member_id` INT UNSIGNED NULL  ,
    `visible_status` CHAR(10) NULL DEFAULT 'public' ,
    `insert_dt` datetime NOT NULL  DEFAULT CURRENT_TIMESTAMP,
    `update_dt` datetime NULL  on update CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;    
```
그리고, 데이터베이스 테이블을 바로 생성하는 것도 가능합니다. [시작하기](https://github.com/freetercoder/fca/wiki/%EC%8B%9C%EC%9E%91%ED%95%98%EA%B8%B0) 를 참고하세요.

## API 생성
### 입력
```sh
article title content
```
### 자동 생성 결과
#### 목록 반환하기
```PHP
/*
GET /article
*/  
function _get_list(){
    $query = "select id, title, content, member_id from article where 1 = 1 and visible_status = 'public' order by insert_dt asc";
    return FDB::query_all($query);
}
```
  
**RESPONSE**
```JSON  
[
    {
        "id": "2",
        "title": "sample title",
        "content": "this is sample content",
        "member_id": "1"
    },
    {
        "id": "3",
        "title": "sample title 2",
        "content": "this is sample content 2",
        "member_id": "1"
    }
]
```

#### 싱글 아이템 반환하기
```PHP
/*
GET /article/{id}
*/
function _get_item(){
    $id = FRequest::path_or_400("0: id");
    $query = "select title, content, member_id from article where 1 = 1 and visible_status = 'public' and id = :id";
    return FDB::query_first($query, ["id" => $id]);
}
```
  
**RESPONSE**
```JSON
{
    "title": "sample title",
    "content": "this is sample content",
    "member_id": "1"
}
```


#### 경로 매개변수에 따라 분기하기
```PHP
/*GET /article` or `GET /article/{id}
*/
function get(){
    return FRequest::path(0) == null ? _get_list() : _get_item();
}
```

#### 데이터 생성하기
```PHP
/*
POST /article
Authorization bearer 657fdc1c1ae3a3.19476978

{
    "title": "sample title",
    "content" : "this is sample content"
}
*/
function post(){    
    $params = FRequest::param_or_400("title: article.title", "content: article.content");

    $member = FAuth::member_exist_or_401();
        
    $params["member_id"] = $member["id"];
    $article = FDB::insert_and_return_first("article", $params, "id", "title", "content", "member_id");
    return $article;
}
```
  
**RESPONSE**
```JSON
{
    "id": "4",
    "title": "sample title 3",
    "content": "this is sample content 3",
    "member_id": "1"
}
```

#### 데이터 수정하기
```PHP
/*
PUT /article/1
Authorization bearer 657fdc1c1ae3a3.19476978

{
    "title": "sample title mod",
    "content" : "this is sample content mod"
}
*/
function put(){    
    $params = FRequest::param_or_400("0: id : id", "title: article.title", "content: article.content");
    $article = FDB::first_or_404("article", "id", $params["id"]);
    FAuth::member_owner_or_400($article["member_id"]);

    $article = FDB::update_and_return_first("article", $params, "title", "content", "member_id");
    return $article;
}
```
  
**RESPONSE**
```JSON
{
    "title": "sample title mod",
    "content": "this is sample content mod",
    "member_id": "1"
}
```

#### 데이터 삭제하기
```PHP
/*
DELETE /article/1
Authorization bearer 657fdc1c1ae3a3.19476978
*/
function delete(){
    $id = FRequest::path_or_400("0: id");
    $article = FDB::first_or_404("article", "id", $id);
    FAuth::member_owner_or_400($article["member_id"]);    
    $query_result = FDB::delete("article", "id", $id);
    return FResponse::_200_or_500($query_result);
}
```
  
**RESPONSE**
```JSON
true
```

# 튜토리얼
[하나씩 따라하기](https://github.com/freetercoder/fca/wiki/%EC%8B%9C%EC%9E%91%ED%95%98%EA%B8%B0)  

# API
[API 목록](https://github.com/freetercoder/fca/wiki/API-%EB%AA%A9%EB%A1%9D)


# Deploy

# License