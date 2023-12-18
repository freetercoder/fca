**FCA :: The simplest and easiest PHP framework to build APIs.**

# README for each language
[Korean](https://github.com/freetercoder/fca/blob/main/README.md)
[English](https://github.com/freetercoder/fca/blob/main/docs/readme/en.md)

# Project Summary
FCA (**F**reeter **C**oder **A**PI) is a PHP framework that helps you build APIs in the simplest and easiest way.
As long as you have PHP and MySQL installed, you can build a simple API server in less than 5 minutes.

The advantages of FCA include:

1. No complicated terminal or commands required. Everything can be done on the web.
2. You can create a database table with a simple command.
3. API creation is possible based on database tables.
4. You don’t need to know MVC. API development is possible using the vanilla PHP method.
5. No need for Composer.

# sample code
## Create table
### input
```
article title content
```
### Output result
```
CREATE TABLE `article`
(
     `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
     `title` VARCHAR(255) NULL ,
     `content` VARCHAR(255) NULL ;
     `member_id` INT UNSIGNED NULL ,
     `visible_status` CHAR(10) NULL DEFAULT 'public' ,
     `insert_dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `update_dt` datetime NULL on update CURRENT_TIMESTAMP,

     PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_general_ci;
```
Additionally, it is also possible to create database tables directly.

## Create API
### input
```
article title content
```
### Output result
```
// internal function for get list.
function_get_list()
```
  
```
// internal function for get item.
function_get_item(){
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

# GettingStarted
[Korean](https://github.com/freetercoder/fca/blob/main/docs/getting_started/ko.md)
[English](https://github.com/freetercoder/fca/blob/main/docs/getting_started/en.md)

# API Reference
[Korean](https://github.com/freetercoder/fca/blob/main/docs/api_reference/ko.md)
[English](https://github.com/freetercoder/fca/blob/main/docs/api_reference/en.md)

# Deploy

# License