<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "VoteTypeRestService.php");

class VoteTypeRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new VoteTypeRestService( $this );
        $this->base = "voteType";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/search/byCategory/(?P<categoryId>[-]?[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'search' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    )
                )
            )
        ));

        register_rest_route( $this->namespace, '/' . $this->base . '/move', array(
            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this->service, 'move' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => array(

                    'context'          => $this->get_context_param(),

                    "id" => array(
                        'description'        => __( 'vote type id to move.' ),
                        'type'               => 'integer',
                        'sanitize_callback'  => 'absint'
                    ),

                    "id_other" => array(
                        'description'        => __( 'other vote type id to update.' ),
                        'type'               => 'string',
                        'default'            => "",
                        'validate_callback'  => 'rest_validate_request_arg'
                    ),

                    "id_category" => array(
                        'description'        => __( 'vote type id category to move.' ),
                        'type'               => 'integer',
                        'sanitize_callback'  => 'absint'
                    ),

                    "mode" => array(
                        'description'        => __( 'mode to move.' ),
                        'type'               => 'string',
                        'validate_callback'  => 'rest_validate_request_arg'
                    )
                )
             )
        ));

    }

}
