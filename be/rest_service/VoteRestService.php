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

    private $format = array(
        "id" => "%d",
        "vote_value" => "%s",
        "id_vote_types" => "%d"
    );

    function __construct()
    {
        parent :: __construct(new VoteDao());
    }

    function prepareForDb($filter) {

        $id = parent::getProp("id", $filter);
        if($id != null) {
            parent::setProp("id", $filter, $id);
        }

        $vote_value = parent::getProp("vote_value", $filter);
        if($vote_value != null) {
            parent::setProp("vote_value", $filter, $vote_value);
        }

        $id_vote_types = parent::getProp("id_vote_types", $filter);
        if($id_vote_types != null) {
            parent::setProp("id_vote_types", $filter, $id_vote_types);
        }

        return $filter;
    }

    function prepareForResponse($filter, $request) {

        return $this->prepareForDb($filter);
    }

    function addReviewVote($item, $votes) {

        $ret = array();

        foreach ( $votes as $vote) {

            $vote["id_item"] = $item["id"];
            $voteDb = $this->prepareForDb($vote);

            $voteOrError = $this->dao->create($voteDb, $this->format);
            if (get_class($voteOrError) == "WP_Error") {
                return $voteOrError;
            }

            $ret[] = $voteOrError;
        }

        return $ret;

    }

    function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = $this->format[$d];
        }
        return $format;
    }
}