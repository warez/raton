<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "ItemDao.php");

class ItemRestService extends BaseRestService {

    protected $itemFormat = array(
        "id" => "%d",
        "title" => "%s",
        "description" => "%s",
        "image" => "%s",
        "insert_date" => "%d",
        "last_update_date" => "%d",
        "id_category" => "%d",
        "approved" => "%s",
        "request_approve" => "%s"
    );

    function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = $this->itemFormat[$d];
        }
        return $format;
    }

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

    function search($request) {

        try {

            $page = $request->get_param("page");
            $itemPerPage = $request->get_param("per_page");;
            $title = $request->get_param("title");
            $description = $request->get_param("description");
            $request_approve_type = $request->get_param("request_approve_type");
            $approved_type = $request->get_param("approved_type");
            $from = $request->get_param("from");
            $creationTimeCond = $request->get_param("creationTimeCond");
            $creationTime = $request->get_param("creationTime");
            $updateTimeCond = $request->get_param("updateTimeCond");
            $updateTime = $request->get_param("updateTime");

            if($page == null || !is_numeric($page))
                return new WP_Error( "search_item_0", "Page number is null or not a number" , array( 'status' => 500 ) );

            if($itemPerPage == null || !is_numeric($itemPerPage))
                return new WP_Error( "search_item_1", "Item per page number is null or not a number" , array( 'status' => 500 ) );

            $ret = $this->dao->search($title, $description,
                $request_approve_type, $approved_type, $from,
                $creationTimeCond, $creationTime,$updateTimeCond,$updateTime,
                $page, $itemPerPage);

            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "search_item" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function getCategoryItems($request) {

        try {

            $page = $request->get_param("page");
            $itemPerPage = $request->get_param("per_page");;
            $catId = $request->get_param("id");

            if($page == null || !is_numeric($page))
                return new WP_Error( "get_cat_items_0", "Page number is null or not a number" , array( 'status' => 500 ) );

            if($itemPerPage == null || !is_numeric($itemPerPage))
                return new WP_Error( "get_cat_items_1", "Item per page number is null or not a number" , array( 'status' => 500 ) );

            if($catId == null || !is_numeric($catId))
                return new WP_Error( "get_cat_items_2", "Category id is null or not a number" , array( 'status' => 500 ) );

            $ret = $this->dao->getCategoryItems($catId, $page, $itemPerPage);
            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "get_cat_items" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function prepareForResponse($item, $request) {

        return $this->prepareForDb($item);
    }

}