<?php

class VoteTypeResult {

    public $pageInfo, $voteTypes;

    function __construct($voteTypes, $pageInfo) {
        $this->voteTypes = $voteTypes;
        $this->pageInfo = $pageInfo;
    }

}