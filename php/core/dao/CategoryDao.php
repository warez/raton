<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class CategoryDao extends DaoBase {

    function __construct() {

        parent::__construct("categories", "id");
    }

}