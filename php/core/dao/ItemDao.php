<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class ItemDao extends DaoBase {

    function __construct() {

        parent::__construct("items", "id");
    }

    function testItemPresentInCategory($idListOrId) {

        global $wpdb;

        if(is_array($idListOrId) ) {

            if(count($idListOrId) == 0)
                throw new Exception("No id for delete...");

            $ids = join(',', $idListOrId);
            $cond = "id_category in " . join(',', $idListOrId);

        } else {

            $ids = $idListOrId;
            $cond = "id_category = " . $idListOrId;
        }

        $query = " SELECT count(id) FROM " . $this->tableName . " WHERE " . $cond;
        $retCount = $wpdb->get_var($query);

        if($retCount > 0) {

            throw new Exception('Item exist in one of category with ids: ' . $ids . ". Categories not deleted.");

        }

        return;
    }

    function update($data, $format) {

        try {

            parent::testIdPresent($data);
            parent::testCategoryPresent($data);

            return parent::update($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function create($data, $format)
    {

        try {

            parent::testCategoryPresent($data);
            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}