<?php

class Category {

    public $id, $title, $description,
        $id_parent_category, $is_main_category;

    static private $format = array(
        "id" => "%d",
        "title" => "%s",
        "description" => "%s",
        "id_parent_category" => "%d",
        "is_main_category" => "%d"
    );

    function __construct() {

    }

    static function getFormat() {
        return Category::$format;
    }
}