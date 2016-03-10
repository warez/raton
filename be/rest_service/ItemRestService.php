<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["MODEL"] . "Item.php");
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "ItemDao.php");

class ItemRestService extends BaseRestService {

    function __construct($restController)
    {
        parent :: __construct($restController, new ItemDao());
    }

    function prepareForDb($item) {

        $id = parent::getProp("id", $item);
        if($id != null) {
            parent::setProp("id", $item, $id);
        }

        $id_parent_category = parent::getProp("id_parent_category", $item);
        if($id_parent_category != null) {
            parent::setProp("id_parent_category", $item, $id_parent_category);
        }

        return $item;
    }

    function getCategoryItems($request) {

        try {

            $page = $request->get_param("page");
            $itemPerPage = $request->get_param("per_page");;
            $catId = $request->get_param("id");

            if($page == null || !is_numeric($page))
                return new WP_Error( 0, "Page number is null or not a number" , array( 'status' => 500 ) );

            if($itemPerPage == null || !is_numeric($itemPerPage))
                return new WP_Error( 1, "Item per page number is null or not a number" , array( 'status' => 500 ) );

            if($catId == null || !is_numeric($catId))
                return new WP_Error( 2, "Category id is null or not a number" , array( 'status' => 500 ) );

            $ret = $this->dao->getCategoryItems($catId, $page, $itemPerPage);
            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "0" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function prepareForResponse($item, $request) {

        return $this->prepareForDb($item);
    }

    function getFormat($data) {
        return Item::getFormat($data);
    }

}