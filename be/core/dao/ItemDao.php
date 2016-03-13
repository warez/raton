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

    function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    function search($title, $description,
                    $request_approve_type, $approved_type, $from,
                    $page = 0, $itemPerPage = 10) {

        global $wpdb;

        try {

            ob_start();

            $page = $page - 1;

            if($page < 0) {
                $page = 0;
                $itemPerPage = 18446744073709551615;
            }

            $whereCond = '';
            $params = array();

            if(trim($title) != "") {
                $whereCond .= " title like %s and ";
                $params[] = "%" . $title . "%";
            }

            if(trim($description) != "") {
                $whereCond .= " description like %s and ";
                $params[] = "%" . $description . "%";
            }

            if($request_approve_type != "a") {
                $whereCond .= " request_approve = %s and ";
                $params[] = $request_approve_type;
            }

            if(trim($approved_type) != "a") {
                $whereCond .= " approved = %s and ";
                $params[] = $approved_type;
            }

            if($from != -1) {
                $whereCond .= " id_category = %d and ";
                $params[] = $from;
            }

            if($this->endsWith($whereCond, " and "))
                $whereCond = substr($whereCond,0, strlen($whereCond) - 4);
            if(strlen($whereCond) > 0)
                $whereCond = " where " . $whereCond;

            $firstItem = $page * $itemPerPage;

            $queryCount = $wpdb->prepare(" SELECT count(*) FROM " . $this->tableName . $whereCond, $params);
            $retCount = $wpdb->get_var($queryCount);
            $data = array("items"=> array(), "total_count"=>$retCount , "page"=>$page + 1 , "itemPerPage"=>$itemPerPage);

            if($retCount == 0) {
                return new WP_REST_Response($data);
            }

            $params[] = $firstItem;
            $params[] = $itemPerPage;

            $query = $wpdb->prepare(
                " SELECT * FROM " . $this->tableName .
                " " . $whereCond . " order by id desc LIMIT %d,%d", $params);

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
            //TODO test vote....

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