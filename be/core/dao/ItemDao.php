<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class ItemDao extends DaoBase {

    function __construct() {

        parent::__construct("items", "id");
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

    function getCategoryItems($categoryId, $page = 0, $itemPerPage = 10) {

        global $wpdb;

        try {

            ob_start();

            $page = $page - 1;

            if($page < 0) {
                $page = 0;
                $itemPerPage = 18446744073709551615;
            }

            $firstItem = $page * $itemPerPage;

            $queryCount = $wpdb->prepare(" SELECT count(*) FROM " . $this->tableName . " WHERE id_category = %d", $categoryId);
            $retCount = $wpdb->get_var($queryCount);
            $data = array("items"=> array(), "total_count"=>$retCount , "page"=>$page , "itemPerPage"=>$itemPerPage);

            if($retCount == 0) {
                return new WP_REST_Response($data);
            }

            $query = $wpdb->prepare(
                " SELECT * FROM " . $this->tableName .
                " WHERE id_category = %d order by id desc LIMIT %d,%d", $categoryId, $firstItem, $itemPerPage);

            $result = $wpdb->get_results($query, OBJECT);

            if ($result == null) {
                return new WP_REST_Response($data);
            }

            $data = array("items"=>$result, "total_count"=>$retCount , "page"=>$page + 1 , "itemPerPage"=>$itemPerPage);
            return new WP_REST_Response($data);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }
    }

    function delete($id)
    {

        try {

            $data = array("id" => $id);
            $categoryType = new CategoryDao();

            parent::testIdPresent($data);

            $item = parent::get($id);
            $categoryType->testParentCategory($item["id_category"]);

            return parent::delete($id);

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