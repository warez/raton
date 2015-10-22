<?php

require_once($raton_dir["MODEL"] . "BaseVO.php");

class Item extends BaseVO{

    public $title = "";

    function __construct($title = "") {
        $this->title = $title;
    }

}