<?php
$GLOBALS["user_fields"] = [
    "id" => ffield()->required()->type_of_int()->min(0),
    "article.title" => ffield()->required()->len_min(5)->len_max(100),
    "article.content" => ffield()->len_range(10, 500)
];