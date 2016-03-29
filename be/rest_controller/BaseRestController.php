<?php

global $raton_dir;

class BaseRestController extends WP_REST_Controller {

    protected $namespace, $version, $base = null, $service;

    function __construct($version) {
        $this->version = $version;
        $this->namespace = 'raton/v' . $this->version;
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->base, array(
            array(
                'methods'         => WP_REST_Server::CREATABLE,
                'callback'        => array( $this->service, 'create' ),
                'permission_callback' => array( $this, 'getAdminUserCheck' ),
                'args'            => $this->get_endpoint_args_for_item_schema( true ),
                'accept_json'     => true
            ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->base . '/(?P<id>[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'get' ),
                'permission_callback' => array( $this, 'getAllUserCheck' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    )
                ),
            ),

            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this->service, 'update' ),
                'permission_callback' => array( $this, 'getAdminUserCheck' ),
                'args'            => $this->get_endpoint_args_for_item_schema( false ),
                'accept_json'     => true
            ),

            array(
                'methods'  => WP_REST_Server::DELETABLE,
                'callback' => array( $this->service, 'delete' ),
                'permission_callback' => array( $this, 'getAdminUserCheck' ),
                'args'     => array(
                    'force'    => array(
                        'default'      => false,
                    ),
                ),
            ),
        ) );
    }

    public function getLoggedUserCheck( $request ) {
        return is_user_logged_in();
    }

    public function getAllUserCheck( $request ) {
        return true;
    }

    public function getAdminUserCheck( $request ) {
        return is_user_logged_in() && is_super_admin();
    }

}
