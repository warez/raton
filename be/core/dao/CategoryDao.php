<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");
require_once($raton_dir["DAO"] . "ItemDao.php");
require_once($raton_dir["DAO"] . "VoteTypeDao.php");
require_once($raton_dir["MODEL"] . "CategoryForTree.php");

//16.00 antonella mula armanda biasci

class CategoryDao extends DaoBase {

    private $itemDao;

    function __construct() {

        $this->itemDao = new ItemDao();
        parent::__construct("categories", "id");
    }

    function testParentCategory($id) {

        global $wpdb;

        $cond = "id_parent_category = " . $id;

        $query = " SELECT count(id) FROM " . $this->tableName . " WHERE " . $cond;
        $retCount = $wpdb->get_var($query);

        if($retCount > 0) {

            throw new Exception('One of category with id: ' . $id . " are parent. Categories not deleted.");

        }

        return;
    }

    function testParent($data) {

        if(!array_key_exists("id_parent_category",$data) || $data["id_parent_category"] == null || $data["id_parent_category"] == -1)
            return;

        $idParent = $data["id_parent_category"];
        $parent = parent::get($idParent);

        if(is_object($parent) && get_class($parent) == "WP_Error")
            throw new Exception("Parent category with id: " . $idParent . " not exist.");

        if(array_key_exists("id", $data) && $data["id_parent_category"] == $data["id"])
            throw new Exception("Parent category id and entity id is equals.");

        return;

    }

    private function getAllCategory($parentId = null) {

        global $wpdb;

        $where = "";

        if($parentId != null)
            $where = " where id_parent_category = " . $parentId;

        $countById = $this->itemDao->countItemByCategory();

        $query =  " SELECT * FROM " . $this->tableName .
            " " . $where . " ORDER BY id DESC";

        $result = $wpdb->get_results($query, OBJECT);
        $ret = array();

        foreach ( $result as $cat ) {
            $ret[$cat->id] = $cat;
            $cat->itemCount = $countById[$cat->id];
            if($cat->itemCount == null)
                $cat->itemCount = 0;
        }

        return $ret;
    }

    private function getParentIdByCatId($categories){

        $ret = array();
        $ret[-1] = null;

        foreach ( $categories as $catId => $cat ) {
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

            $rootCat = array();
            $rootCat["id"] = "ROOT";

            $ret[0]->cat = $rootCat;
            return $ret[0];

        } catch(Exception $e) {

            return new WP_Error( "get_cat_tree" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

    function update($data, $format) {

        try {

            $this->testParent($data);
            return parent::update($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "update_cat" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    function create($data, $format)
    {

        try {

            $this->testParent($data);
            return parent::create($data,$format);

        } catch(Exception $e) {

            return new WP_Error( "create_cat" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

    function delete($id)
    {

        try {

            $itemDao = new ItemDao();
            $voteType = new VoteTypeDao();

            $this->testParentCategory($id);
            $itemDao->testObjectPresentInCategory($id);
            $voteType->testObjectPresentInCategory($id);

            return parent::delete($id);

        } catch(Exception $e) {

            return new WP_Error( "delete_cat" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

}