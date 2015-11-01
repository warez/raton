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

    function __construct(array $val) {
        if(array_key_exists("id", $val))
            $this->id = $val["id"];

        if(array_key_exists("title", $val))
            $this->title = $val["title"];

        if(array_key_exists("description", $val))
            $this->description = $val["description"];

        if(array_key_exists("id_parent_category", $val))
            $this->id_parent_category = $val["id_parent_category"];

        if(array_key_exists("is_main_category", $val))
            $this->is_main_category = $val["is_main_category"];
    }

    static function getFormat() {
        return Category::$format;
    }
}