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
        if(property_exists($category, "id"))
            $category->id = intval( $category->id );

        if(property_exists($category, "id_parent_category"))
            $category->id_parent_category = intval( $category->id_parent_category );

        if(property_exists($category, "is_main_category"))
            $category->is_main_category = intval( $category->is_main_category );

        return $category;
    }

    function prepareForResponse($category, $request) {

        $category->id = intval( $category->id );

        if(property_exists($category, "id_parent_category"))
            $category->id_parent_category = intval( $category->id_parent_category );

        if(property_exists($category, "is_main_category"))
            $category->is_main_category = intval( $category->is_main_category );

        return $category;
    }

    function getFormat() {
        return Category::getFormat();
    }

}