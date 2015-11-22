<?php

class PageInfo {

    public $pageSize, $page, $itemCount;

    function __construct($pageSize, $page, $itemCount) {
        $this->pageSize = $pageSize;
        $this->page = $page;
        $this->itemCount = $itemCount;
    }

}