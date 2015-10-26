<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "ItemRestService.php");

class ItemRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new ItemRestService( $this );
        $this->base = "item";
    }

}
