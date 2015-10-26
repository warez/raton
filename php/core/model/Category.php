<?php

require_once($raton_dir["MODEL"] . "BaseVO.php");

class Category extends BaseVO{

    public $id, $title, $description,
        $idParentCategory, $isMainCategory;

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