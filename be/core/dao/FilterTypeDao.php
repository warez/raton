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

    function searchFilterTypeByTitle($title, $pageSize, $page) {

        try {

            global $wpdb, $raton_dir;

            require_once($raton_dir["MODEL"] . "PageInfo.php");
            require_once($raton_dir["MODEL"] . "FilterTypeResult.php");

            ob_start();

            $where = " where title like '%" . $title . "%' ";

            $queryCount = " SELECT count(*) FROM " . $this->tableName . $where;
            $retCount = $wpdb->get_var($queryCount);
            $retCount = intval($retCount);

            $start = $page == 0 ? 0 : $pageSize * $page;
            $end = $pageSize;
            $limit = $pageSize == -1 ? "" : " LIMIT $start,$end ";

            $query = " SELECT * FROM " . $this->tableName .
                " $where ORDER BY id DESC $limit";

            $result = $wpdb->get_results($query, OBJECT);

            $pageInfo = new PageInfo($pageSize, $page , $retCount);
            $ret = new FilterTypeResult($result, $pageInfo);

            return $ret;

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

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