<?php

global $raton_dir;

require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "ReviewRestService.php");

class ReviewRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new ReviewRestService();
        $this->base = "review";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/search/byItem/(?P<itemId>[-]?[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this->service, 'search' ),
                'permission_callback' => array( $this, 'getAllUserCheck' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    )
                )
            )
        ));
    }

}
