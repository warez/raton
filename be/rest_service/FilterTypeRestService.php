<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["MODEL"] . "FilterType.php");
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "FilterTypeDao.php");

class FilterTypeRestService extends BaseRestService {

    function __construct($restController)
    {
        parent :: __construct($restController, new FilterTypeDao());
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

        $filter_args = parent::getProp("filter_args", $filter);
        if($filter_args != null) {
            parent::setProp("filter_args", $filter, $filter_args);
        }

        $metaType = parent::getProp("meta_type", $filter);
        if($filter_args != null) {
            parent::setProp("meta_type", $filter, $metaType);
        }

        return $filter;
    }

    function searchFilterTypeByTitle($request) {
        try {

            $title = $request->get_param("searchTitle");

            if($title == null || strlen($title)  < 3)
                throw new Exception("Search term is null or size is less than 3");

            $pageSize = $request->get_param("pageSize");
            $page = $request->get_param("page");

            if($pageSize == null || !is_numeric($pageSize))
                $pageSize = parent::$DEFAULT_PAGE_SIZE;
            if($page == null || !is_numeric($page))
                $page = parent::$DEFAULT_PAGE;

            $ret = $this->dao->searchFilterTypeByTitle($title, $pageSize, $page);

            if(is_object($ret) && get_class($ret) == "WP_Error")
                return $ret;

            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "Get Filter by title error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

    function prepareForResponse($filterType, $request) {

        return $this->prepareForDb($filterType);
    }

    function getFormat($data) {
        return FilterType::getFormat($data);
    }
}