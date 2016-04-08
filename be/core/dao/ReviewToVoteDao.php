<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class ReviewToVoteDao extends DaoBase
{

    function __construct()
    {

        parent::__construct("review_votes", "id");
    }

    function deleteFromReview($reviewId) {

        try {

            global $wpdb;
            ob_start();

            $query = " DELETE FROM " . $this->tableName . " WHERE id_review = " . $reviewId;
            $wpdb->query($query);

            return new WP_REST_Response();

        } catch (Exception $e) {
            return new WP_Error("delete_vote", __($e->getMessage()), array('status' => 500));
        }
    }


    function update($data, $format)
    {

        try {

            parent::testIdPresent($data);
            return parent::update($data, $format);

        } catch (Exception $e) {

            return new WP_Error("update_review_to_vote", __($e->getMessage()), array('status' => 500));

        }

    }

}