<?php

class FilterTypeResult {

    public $pageInfo, $filterTypes;

    function __construct($filterTypes, $pageInfo) {
        $this->filterTypes = $filterTypes;
        $this->pageInfo = $pageInfo;
    }

}