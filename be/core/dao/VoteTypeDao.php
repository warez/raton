<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");
require_once($raton_dir["DAO"] . "FilterTypeDao.php");

class VoteTypeDao extends DaoBase {

    function __construct() {

        parent::__construct("votes_types", "id");
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

    function searchVoteTypeByCategory($idCat, $pageSize = null,
                                      $page = null) {

        try {

            global $wpdb, $raton_dir;

            require_once($raton_dir["MODEL"] . "PageInfo.php");
            require_once($raton_dir["MODEL"] . "VoteTypeResult.php");

            ob_start();

            $limit = "";
            $order = " position ASC ";
            $where = " where id_category = $idCat";

            if($idCat == -1) {
                $where = "";
                $order = " id DESC";

                $queryCount = " SELECT count(*) FROM " .
                    $this->tableName . " $where";

                $retCount = $wpdb->get_var($queryCount);
                $retCount = intval($retCount);

                $start = $page == 0 ? 0 : $pageSize * $page;
                $end = $pageSize;
                $limit = $pageSize == -1 ? "" : " LIMIT $start,$end ";
            }

            $query = "SELECT *" .
                " FROM " . $this->tableName .
                " $where ORDER BY $order $limit";

            $result = $wpdb->get_results($query, OBJECT);

            if($idCat != -1)
                return $result;

            $pageInfo = new PageInfo($pageSize, $page , $retCount);
            $ret = new VoteTypeResult($result, $pageInfo);

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

            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}