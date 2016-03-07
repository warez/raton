<?php

class Filter {

    public $id, $title, $description, $id_category,
        $id_type, $mandatory, $position;

    static private $format = array(
        "id" => "%d",
        "title" => "%s",
        "description" => "%s",
        "position" => "%d",
        "mandatory" => "%d",
        "id_type" => "%d",
        "id_category" => "%d"
    );

    function __construct(array $val) {
        if(array_key_exists("id", $val))
            $this->id = $val["id"];

        if(array_key_exists("title", $val))
            $this->title = $val["title"];

        if(array_key_exists("description", $val))
            $this->description = $val["description"];

        if(array_key_exists("position", $val))
            $this->position = $val["position"];

        if(array_key_exists("mandatory", $val))
            $this->mandatory = $val["mandatory"];

        if(array_key_exists("id_type", $val))
            $this->id_type = $val["id_type"];

        if(array_key_exists("id_category", $val))
            $this->id_category = $val["id_category"];
    }

    static function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = Filter::$format[$d];
        }
        return $format;
    }

}