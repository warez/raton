<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "VoteDao.php");

class VoteRestService extends BaseRestService {

    function __construct()
    {
        parent :: __construct(new VoteDao());
    }

    function addReviewVote($votes) {

        $ret = array();

        foreach ( $votes as $vote) {

            $vote["vote_value"] = json_encode( $vote["vote_value"] );

            $voteOrError = $this->dao->create($vote, $this->format);
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

    function getFormat() {
        return array(
            "id" => "%d",
            "vote_value" => "%s",
            "id_vote_types" => "%d"
        );
    }
}