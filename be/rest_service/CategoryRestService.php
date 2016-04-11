<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "CategoryDao.php");

class CategoryRestService extends BaseRestService {

    function __construct()
    {
        parent :: __construct(new CategoryDao());
    }

    function getFormat() {
        return array(
            "id" => "%d",
            "title" => "%s",
            "description" => "%s",
            "id_parent_category" => "%d",
            "id_user_create" => "%d",
            "name_user_create" => "%s",
            "id_user_last_update" => "%d",
            "name_user_last_update" => "%s"
        );
    }

    function getCategoryTree($request) {

        try {

            $from = $request->get_param("from");
            if($from == null || !is_numeric($from))
                $from = -1;

            $ret = $this->dao->getCategoryTree($from);

            if(is_object($ret) && get_class($ret) == "WP_Error")
                return $ret;

            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "get_category_tree" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }
}