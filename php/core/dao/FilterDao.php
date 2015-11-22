<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");
require_once($raton_dir["DAO"] . "FilterTypeDao.php");

class FilterDao extends DaoBase {

    function __construct() {

        parent::__construct("search_filters", "id");
    }

    function update($data, $format) {

        try {

            parent::testIdPresent($data);
            parent::testCategoryPresent($data);
            $this->testFilterTypePresent($data);

            return parent::update($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    private function testFilterTypePresent($data) {

        if(!array_key_exists("id_type",$data) || $data["id_type"] == null)
            throw new Exception("Id type is null!");

        $idType = $data["id_type"];
        $filterTypeDao = new FilterTypeDao();
        $cat = $filterTypeDao->get($idType);

        if(is_object($cat) && get_class($cat) == "WP_Error")
            throw new Exception("Filter type with id: " . $idType . " not exist.");

        return;

    }

    function searchFilterByCategory($idCat, $pageSize, $page) {

        try {

            global $wpdb, $raton_dir;

            require_once($raton_dir["MODEL"] . "PageInfo.php");
            require_once($raton_dir["MODEL"] . "FilterResult.php");

            ob_start();

            $where = " where F.id_category = $idCat";
            if($idCat == -1)
                $where = "";

            $queryCount = " SELECT count(*) FROM " .
                $this->tableName . " as F $where";

            $retCount = $wpdb->get_var($queryCount);
            $retCount = intval($retCount);

            $start = $page == 0 ? 0 : $pageSize * $page;
            $end = $pageSize;
            $limit = $pageSize == -1 ? "" : " LIMIT $start,$end ";

            $query = " SELECT F.*, FT.id as filter_type_id, " .
                " FT.title as filter_type_title, ".
                " FT.filter_args as filter_type_args, " .
                " FT.meta_type as filter_type_meta_type" .
                " FROM " . $this->tableName .
                " AS F INNER JOIN " . $wpdb->prefix .
                "search_filters_types AS FT ON F.id_type = FT.id " .
                "$where ORDER BY F.id DESC $limit";

            $result = $wpdb->get_results($query, OBJECT);

            $pageInfo = new PageInfo($pageSize, $page , $retCount);
            $ret = new FilterResult($result, $pageInfo);

            return $ret;

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

    function create($data, $format)
    {

        try {

            parent::testCategoryPresent($data);
            $this->testFilterTypePresent($data);

            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}