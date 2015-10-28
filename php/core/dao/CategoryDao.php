<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class CategoryDao extends DaoBase {

    function __construct() {

        parent::__construct("categories", "id");
    }

    function testParent($data) {

        if(!array_key_exists("id_parent_category",$data) || $data["id_parent_category"] == null)
            return;

        $idParent = $data["id_parent_category"];
        $parent = parent::get($idParent);

        if(is_object($parent) && get_class($parent) == "WP_Error")
            throw new Exception("Parent category with id: " . $idParent . " not exist.");

        if($data["id_parent_category"] == $data["id"])
            throw new Exception("Parent category id and entity id is equals.");

        return;

    }

    function update($data, $format) {

        try {

            $this->testParent($data);
            return parent::update($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function create($data, $format)
    {

        try {

            $this->testParent($data);
            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}