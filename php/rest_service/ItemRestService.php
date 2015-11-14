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

        $is_main_category = parent::getProp("is_main_category", $item);
        if($is_main_category != null) {
            parent::setProp("is_main_category", $item, $is_main_category);
        }

        return $item;
    }

    function prepareForResponse($item, $request) {

        return $this->prepareForDb($item);
    }

    function getFormat($data) {
        return Item::getFormat($data);
    }

}