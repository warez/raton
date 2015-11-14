<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class ItemDao extends DaoBase {

    function __construct() {

        parent::__construct("items", "id");
    }

    function testId($data) {
        if(!array_key_exists("id",$data) || $data["id"] == null)
            throw new Exception("Item id is null!");

        $id = $data["id"];
        $item = $this->get($id);

        if(is_object($item) && get_class($item) == "WP_Error")
            throw new Exception("Item with id: " . $id . " not exist.");

        return null;
    }

    function testCategory($data) {

        if(!array_key_exists("id_category",$data) || $data["id_category"] == null)
            throw new Exception("Category is null!");

        $idCategory = $data["id_category"];
        $categoryDao = new CategoryDao();
        $cat = $categoryDao->get($idCategory);

        if(is_object($cat) && get_class($cat) == "WP_Error")
            throw new Exception("Category with id: " . $idCategory . " not exist.");

        return;

    }

    function update($data, $format) {

        try {

            $this->testId($data);
            $this->testCategory($data);

            return parent::update($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function create($data, $format)
    {

        try {

            $this->testCategory($data);
            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}