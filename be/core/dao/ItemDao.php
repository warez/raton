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

            $date = date('YmdHis', time());
            $data["last_update_date"]= $date;

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
                    $creationTimeCond, $creationTime,$updateTimeCond,$updateTime,
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

            if($creationTimeCond != null && $creationTime != null) {
                if($creationTimeCond == "before" ) {
                    $whereCond .= " insert_date <= %f and ";
                    $params[] = $creationTime;
                } else if($creationTimeCond == "after" ) {
                    $whereCond .= " insert_date >= %f and ";
                    $params[] = $creationTime;
                } else if($creationTimeCond == "at" ) {
                    $whereCond .= " insert_date >= %f and insert_date <= %f and ";
                    $startDay = substr("" . $creationTime, 0, 8) . "000000";
                    $endDay = substr("" . $creationTime, 0, 8) . "235959";
                    $params[] = floatval($startDay);
                    $params[] = floatval($endDay);
                }
            }

            if($updateTimeCond != null && $updateTime != null) {
                if($updateTimeCond == "before" ) {
                    $whereCond .= " last_update_date <= %f and ";
                    $params[] = $updateTime;
                } else if($updateTimeCond == "after" ) {
                    $whereCond .= " last_update_date >= %f and ";
                    $params[] = $updateTime;
                } else if($updateTimeCond == "at" ) {
                    $whereCond .= " last_update_date >= %f and last_update_date <= %f and ";
                    $startDay = substr("" . $updateTime, 0, 8) . "000000";
                    $endDay = substr("" . $updateTime, 0, 8) . "235959";
                    $params[] = $startDay;
                    $params[] = $endDay;
                }
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

            $date = date('YmdHis', time());
            $data["insert_date"]= $date;
            $data["last_update_date"]= $date;

            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}