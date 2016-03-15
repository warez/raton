<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

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

            return new WP_Error( "update_vote_type" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function search($from) {

        global $wpdb;

        try {

            ob_start();

            $whereCond = " where id_category = %d";
            $params = array();
            $params[] = $from;

            $queryCount = $wpdb->prepare(" SELECT count(*) FROM " . $this->tableName . $whereCond, $params);
            $retCount = $wpdb->get_var($queryCount);
            $data = array("items"=> array(), "total_count"=>$retCount);

            if($retCount == 0) {
                return new WP_REST_Response($data);
            }

            $query = $wpdb->prepare(
                " SELECT * FROM " . $this->tableName .
                " " . $whereCond . " order by position desc", $params);

            $result = $wpdb->get_results($query, OBJECT);

            if ($result == null) {
                return new WP_REST_Response($data);
            }

            $data = array("items"=>$result, "total_count"=>$retCount);
            return new WP_REST_Response($data);

        } catch(Exception $e) {

            return new WP_Error( "search_vote_type" , __( $e->getMessage() ), array( 'status' => 500 ) );

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

            return new WP_Error( "create_vote_type" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}