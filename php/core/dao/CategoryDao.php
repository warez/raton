<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");
require_once($raton_dir["MODEL"] . "CategoryForTree.php");
require_once($raton_dir["MODEL"] . "Category.php");


class CategoryDao extends DaoBase {

    function __construct() {

        parent::__construct("categories", "id");
    }

    function testParent($data) {

        if(!array_key_exists("id_parent_category",$data) || $data["id_parent_category"] == null)
            return;

        $idParent = $data["id_parent_category"];
        $parent = parent::get($idParent);

        if(is_object($parent) && get_class($parent) == "WP_Error")
            throw new Exception("Parent category with id: " . $idParent . " not exist.");

        if($data["id_parent_category"] == $data["id"])
            throw new Exception("Parent category id and entity id is equals.");

        return;

    }

    private function getAllCategory() {

        global $wpdb;

        $query =  " SELECT * FROM " . $this->tableName .
            " ORDER BY id ASC";

        $result = $wpdb->get_results($query, OBJECT);
        $ret = array();

        foreach ( $result as $cat ) {
            $ret[$cat->id] = $cat;
        }

        return $ret;
    }

    private function getParentIdByCatId($categories){

        $ret = array();
        $ret[-1] = null;

        foreach ( $categories as $catId => $cat ) {

            if($cat->is_main_category == 0)
                $cat->id_parent_category = -1;

            $ret[ $catId ] = intval($cat->id_parent_category,10);
        }

        return $ret;
    }

    private function getRootCategory() {
        $arr = array();
        $arr["id"] = "ROOT";

        return new Category($arr);
    }

    private function getCategoryTree2($allCat, $tree, $root) {

        $return = null;

        foreach($tree as $childId => $parentId) {

            if($parentId == $root) {

                $temp = $allCat[$childId];
                unset($tree[$childId]);

                $subCat = $this->getCategoryTree2($allCat, $tree, $childId);
                $return[] = new CategoryForTree($temp, $subCat);
            }

        }

        return empty($return) ? null : $return;
    }

    function getCategoryTree($fromCatId) {

        try {

            ob_start();

            $allCat = $this->getAllCategory();
            $parIdByCatId = $this->getParentIdByCatId($allCat);

            $ret = $this->getCategoryTree2($allCat, $parIdByCatId, null);
            if($ret == null || empty($ret))
                return null;

            $ret[0]->cat = $this->getRootCategory();
            return $ret[0];

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

    function update($data, $format) {

        try {

            $this->testParent($data);
            return parent::update($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function create($data, $format)
    {

        try {

            $this->testParent($data);
            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "Business error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}