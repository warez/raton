<?php

class Item {

    public $id, $title, $description,
        $image, $insertDate, $idCategory;

    static private $format = array(
        "id" => "%d",
        "title" => "%s",
        "description" => "%s",
        "image" => "%s",
        "insert_date" => "%s",
        "id_category" => "%d",
        "approved" => "%s",
        "request_approve" => "%s"
    );

    function __construct(array $val) {
        if(array_key_exists("id", $val))
            $this->id = $val["id"];

        if(array_key_exists("title", $val))
            $this->title = $val["title"];

        if(array_key_exists("description", $val))
            $this->description = $val["description"];

        if(array_key_exists("image", $val))
            $this->image = $val["image"];

        if(array_key_exists("insertDate", $val))
            $this->insertDate = $val["insertDate"];

        if(array_key_exists("idCategory", $val))
            $this->idCategory = $val["idCategory"];
    }

    static function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = Item::$format[$d];
        }
        return $format;
    }

}