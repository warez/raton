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


    /*
SELECT r.id_item, avg(v.vote) FROM wp_reviews as r
left join wp_review_votes as rv on r.id = rv.id_review
left join wp_votes as v on v.id = rv.id_vote
where r.id_item in (5,10)
group by r.id_item, r.id_user
     */

    /*function search_user($itemsId, $page = 0, $itemPerPage = 10) {

        global $wpdb;

        try {

            $page = $page - 1;
            $firstItem = $page * $itemPerPage;

            $queryCount = $wpdb->prepare(" SELECT count(*) FROM " . $this->tableName . " where id_category =  ", $params);
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