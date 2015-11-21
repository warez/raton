<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class FilterTypeDao extends DaoBase {

    function __construct() {

        parent::__construct("search_filters_types", "id");
    }

    function update($data, $format) {

        try {

            parent::testIdPresent($data);
            $this->testMetaType($data);

            return parent::update($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function searchFilterTypeByTitle($title) {

        global $wpdb;

        $query =  " SELECT * FROM " . $this->tableName .
            "  where title like '%" . $title . "%' ORDER BY id ASC";

        $result = $wpdb->get_results($query, OBJECT);

        return $result;

    }

    private function testMetaType($data) {

        if(!array_key_exists("meta_type",$data) || $data["meta_type"] == null)
            throw new Exception("Meta type is null!");

        $metaType = $data["meta_type"];
        if($metaType != "TEXT" &&
            $metaType != "COMBO" &&
            $metaType != "NUMERIC")

            throw new Exception("Invalid meta type, supported meta-type are: NUMERIC, COMBO, TEXT");

        return;
    }

    function create($data, $format)
    {

        try {

            $this->testMetaType($data);
            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}