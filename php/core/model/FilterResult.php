<?php

class FilterResult {

    public $pageInfo, $filters;

    function __construct($filters, $pageInfo) {
        $this->filters = $filters;
        $this->pageInfo = $pageInfo;
    }

}