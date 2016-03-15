<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["MODEL"] . "VoteType.php");
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "VoteTypeDao.php");

class VoteTypeRestService extends BaseRestService {

    private $format = array(
        "id" => "%d",
        "title" => "%s",
        "description" => "%s",
        "position" => "%d",
        "vote_limit" => "%d",
        "id_category" => "%d"
    );

    function __construct($restController)
    {
        parent :: __construct($restController, new VoteTypeDao());
    }

    function prepareForDb($filter) {

        $id = parent::getProp("id", $filter);
        if($id != null) {
            parent::setProp("id", $filter, $id);
        }

        $title = parent::getProp("title", $filter);
        if($title != null) {
            parent::setProp("title", $filter, $title);
        }

        $description = parent::getProp("description", $filter);
        if($description != null) {
            parent::setProp("description", $filter, $description);
        }

        $position = parent::getProp("position", $filter);
        if($position != null) {
            parent::setProp("position", $filter, $position);
        }

        $vote_limit = parent::getProp("vote_limit", $filter);
        if($vote_limit != null) {
            parent::setProp("vote_limit", $filter, $vote_limit);
        }

        $id_category = parent::getProp("id_category", $filter);
        if($id_category != null) {
            parent::setProp("id_category", $filter, $id_category);
        }

        return $filter;
    }

    function search($request) {
        try {

            $catId = $request->get_param("categoryId");

            if($catId == null || !is_numeric($catId) || intval($catId) <= 0)
                return new WP_Error( "get_cat_vote_type_1", "Category id is null, less than 0 or not a number" , array( 'status' => 500 ) );

            $ret = $this->dao->search(intval($catId));
            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "get_cat_vote_type" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

    function prepareForResponse($filter, $request) {

        return $this->prepareForDb($filter);
    }

    function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = $this->format[$d];
        }
        return $format;
    }
}