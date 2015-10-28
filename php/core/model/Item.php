<?php

class Item {

    public $id, $title, $description,
        $image, $insertDate, $idCategory;

    static private $format = array(
        "id" => "%d",
        "title" => "%s",
        "insert_date" => "%s",
        "id_category" => "%d"
    );

    function __construct() {

    }

    static function getFormat() {
        return Item::$format;
    }

}