<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "FilterTypeRestService.php");

class FilterTypeRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new FilterTypeRestService( $this );
        $this->base = "filterType";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/search/(?P<searchTitle>.+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'searchFilterTypeByTitle' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    )
                )
            ),
        ) );

    }

}
