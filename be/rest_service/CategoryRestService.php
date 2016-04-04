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

    function prepareForDb($category) {

        $id = parent::getProp("id", $category);
        if($id != null && $id != -1) {
            parent::setProp("id", $category, $id);
        } else {
            parent::setProp("id", $category, null);
        }


        $id_parent_category = parent::getProp("id_parent_category", $category);
        if($id_parent_category != null && $id_parent_category != -1) {
            parent::setProp("id_parent_category", $category, $id_parent_category);
        } else {
            parent::setProp("id_parent_category", $category, null);
        }

        return $category;
    }

    function prepareForResponse($category, $request) {

        return $this->prepareForDb($category);
    }

    private $format = array(
        "id" => "%d",
        "title" => "%s",
        "description" => "%s",
        "id_parent_category" => "%d"
    );

    function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = $this->format[$d];
        }
        return $format;
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