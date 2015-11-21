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

    function prepareForDb($filterType) {

        $id = parent::getProp("id", $filterType);
        if($id != null) {
            parent::setProp("id", $filterType, $id);
        }

        $title = parent::getProp("title", $filterType);
        if($title != null) {
            parent::setProp("title", $filterType, $title);
        }

        $filter_args = parent::getProp("filter_args", $filterType);
        if($filter_args != null) {
            parent::setProp("filter_args", $filterType, $filter_args);
        }

        $metaType = parent::getProp("meta_type", $filterType);
        if($filter_args != null) {
            parent::setProp("meta_type", $filterType, $metaType);
        }

        return $filterType;
    }

    function searchFilterTypeByTitle($request) {
        try {

            $title = $request->get_param("searchTitle");

            if($title == null || strlen($title)  < 3)
                throw new Exception("Search term is null or size is less than 3");

            $ret = $this->dao->searchFilterTypeByTitle($title);

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