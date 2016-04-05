<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

//16.00 antonella mula armanda biasci

class ReviewDao extends DaoBase
{

    private $itemDao;

    function __construct()
    {
        parent::__construct("reviews", "id");
    }

    function getTableName() {
        return $this->tableName;
    }

    /*function addReview($user_id, $item_id, $reviewText, $votesById = array() ) {

        global $wpdb;

        try {

            $data = array();

            parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "search_item" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }*/

    function delete($id)
    {

        try {

            $data = array("id" => $id);
            $revExist = parent::testIdPresent($data);
            if($revExist == null)
                return new WP_Error( "delete_item" , "Review not exist" , array( 'status' => 500 ) );

            return parent::delete($id);

        } catch(Exception $e) {

            return new WP_Error( "delete_item" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

    function create($data, $format)
    {

        try {

            $itemData = array("id_item" => $data["id_item"]);
            $existItem = $this->itemDao->testIdPresent($itemData);
            if($existItem == null)
                return new WP_Error( "create_review" , "Article review not exist" , array( 'status' => 500 ) );

            $date = date('YmdHis', time());
            $data["insert_date"]= $date;

            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "create_item" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}