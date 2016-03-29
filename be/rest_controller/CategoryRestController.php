<?php

global $raton_dir;

require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "CategoryRestService.php");

class CategoryRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new CategoryRestService( $this );
        $this->base = "category";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/tree/(?P<from>[-]?[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'getCategoryTree' ),
                'permission_callback' => array( $this, 'getAllUserCheck' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    )
                )
            ),
        ) );

    }

}
