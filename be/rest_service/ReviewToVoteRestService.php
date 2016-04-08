<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "ReviewToVoteDao.php");

class ReviewToVoteRestService extends BaseRestService {

    private $format = array(
        "id" => "%d",
        "id_review" => "%s",
        "id_vote" => "%d"
    );

    function __construct()
    {
        parent :: __construct(new ReviewToVoteDao());
    }

    function addReviewToVote($reviewId, $votes) {

        $ret = array();

        foreach ( $votes as $vote) {

            $reviewToVote = array();
            $reviewToVote["id_review"] = $reviewId;
            $reviewToVote["id_vote"] = $vote->id;

            $voteOrError = $this->dao->create($reviewToVote, $this->format);
            if (get_class($voteOrError) == "WP_Error") {
                return $voteOrError;
            }

            $ret[] = $voteOrError;
        }

        return $ret;

    }

    function deleteFromReview($reviewId) {
        return $this->dao->deleteFromReview($reviewId);
    }


    function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = $this->format[$d];
        }
        return $format;
    }
}