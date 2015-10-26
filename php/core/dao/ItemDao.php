<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class ItemDao extends DaoBase {

    function __construct() {

        parent::__construct("items", "id");
    }

}