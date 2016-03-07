<?php

class CategoryForTree {

    public $cat;
    public $subCats;

    function __construct($cat, $children) {

        $this->cat = $cat;
        $this->subCats = $children;

    }
}