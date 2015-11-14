<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["MODEL"] . "Category.php");
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once( $raton_dir["DAO"] . "CategoryDao.php");

class CategoryRestService extends BaseRestService {

    function __construct($restController)
    {
        parent :: __construct($restController, new CategoryDao());
    }

    function prepareForDb($category) {

        $id = parent::getProp("id", $category);
        if($id != null) {
            parent::setProp("id", $category, $id);
        }

        $id_parent_category = parent::getProp("id_parent_category", $category);
        if($id_parent_category != null) {
            parent::setProp("id_parent_category", $category, $id_parent_category);
        }

        $is_main_category = parent::getProp("is_main_category", $category);
        if($is_main_category != null) {
            parent::setProp("is_main_category", $category, $is_main_category);
        }

        return $category;
    }

    function prepareForResponse($category, $request) {

        return $this->prepareForDb($category);
    }

    function getFormat($data) {
        return Category::getFormat($data);
    }

}