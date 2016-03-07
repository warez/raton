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

    function searchVoteTypeByCategory($request) {
        try {

            $catId = $request->get_param("categoryId");

            if($catId == null || !is_numeric($catId))
                throw new Exception("Category id is null or not numeric");

            $pageSize = null;
            $page = null;

            if($catId == -1) {
                $pageSize = $request->get_param("pageSize");
                $page = $request->get_param("page");

                if ($pageSize == null || !is_numeric($pageSize))
                    $pageSize = parent::$DEFAULT_PAGE_SIZE;
                if ($page == null || !is_numeric($page))
                    $page = parent::$DEFAULT_PAGE;
            }

            $ret = $this->dao->searchVoteTypeByCategory(
                intval($catId),
                intval($pageSize),
                intval($page)
            );

            if(is_object($ret) && get_class($ret) == "WP_Error")
                return $ret;

            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "Get VoteType by category error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

    function prepareForResponse($filter, $request) {

        return $this->prepareForDb($filter);
    }

    function getFormat($data) {
        return VoteType::getFormat($data);
    }
}