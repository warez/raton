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
        return $item;
    }

    function prepareForResponse($item, $request) {
        return $item;
    }

    function getFormat() {
        return Item::getFormat();
    }

}