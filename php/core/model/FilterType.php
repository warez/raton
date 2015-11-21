<?php

class FilterType {

    public $id, $title, $filter_args, $id_category;

    static private $format = array(
        "id" => "%d",
        "title" => "%s",
        "filter_args" => "%s",
        "meta_type" => "%s"
    );

    function __construct(array $val) {
        if(array_key_exists("id", $val))
            $this->id = $val["id"];

        if(array_key_exists("title", $val))
            $this->title = $val["title"];

        if(array_key_exists("filter_args", $val))
            $this->filter_args = $val["filter_args"];

        if(array_key_exists("meta_type", $val))
            $this->meta_type = $val["meta_type"];
    }

    static function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = FilterType::$format[$d];
        }
        return $format;
    }

}