**FCA :: 가장 단순하고 쉽게 API를 구축하는 PHP 프레임워크.**

# 언어별 README
[한국어](https://github.com/freetercoder/fca/blob/main/README.md)
[English](https://github.com/freetercoder/fca/blob/main/docs/readme/en.md)

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
```
article title content
```
### 출력 결과
```
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
그리고, 데이터베이스 테이블을 바로 생성하는 것도 가능합니다.

## API 생성
### 입력
```
article title content
```
### 출력 결과
```
// internal function for get list.
function _get_list()
```
  
```
// internal function for get item.
function _get_item(){
```
   
```
// GET /article then call get function
function get(){
```
   
```
// POST /article then call post function
function post(){
```
   
```
// PUT /article then call put function
function put(){
```
   
```
// DELETE /article then call post function
function delete(){
```

# 시작하기
[한국어](https://github.com/freetercoder/fca/blob/main/docs/getting_started/ko.md)  
[English](https://github.com/freetercoder/fca/blob/main/docs/getting_started/en.md)

# API 참고
[한국어](https://github.com/freetercoder/fca/blob/main/docs/api_reference/ko.md)
[English](https://github.com/freetercoder/fca/blob/main/docs/api_reference/en.md)

# Deploy

# License