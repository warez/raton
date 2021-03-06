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

    function getFormat() {
        return array(
            "id" => "%d",
            "title" => "%s",
            "description" => "%s",
            "image" => "%s",
            "insert_date" => "%d",
            "last_update_date" => "%d",
            "id_category" => "%d",
            "approved" => "%s",
            "request_approve" => "%s",
            "id_user_create" => "%d",
            "name_user_create" => "%s",
            "id_user_last_update" => "%d",
            "name_user_last_update" => "%s"
        );
    }

    function __construct()
    {
        parent :: __construct(new ItemDao());
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

    function prepareForResponse($item, $op) {

        return $this->prepareForDb($item, $op);
    }

}