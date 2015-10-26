<?php

global $raton_dir;

class BaseRestController extends WP_REST_Controller {

    protected $namespace, $version, $base = null, $service;

    function __construct($version) {
        $this->version = $version;
        $this->namespace = 'raton/v' . $this->version;
    }

    function get_item_schema() {

        return $this->service->getFormat();

    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->base, array(
            array(
                'methods'         => WP_REST_Server::CREATABLE,
                'callback'        => array( $this->service, 'create' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'            => $this->get_endpoint_args_for_item_schema( true ),
                'accept_json'     => true
            ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->base . '/(?P<id>[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'get' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    )
                ),
            ),

            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this->service, 'update' ),
                'permission_callback' => array( $this, 'update_item_permissions_check' ),
                'args'            => $this->get_endpoint_args_for_item_schema( false ),
                'accept_json'     => true
            ),

            array(
                'methods'  => WP_REST_Server::DELETABLE,
                'callback' => array( $this->service, 'delete' ),
                'permission_callback' => array( $this, 'delete_item_permissions_check' ),
                'args'     => array(
                    'force'    => array(
                        'default'      => false,
                    ),
                ),
            ),
        ) );
    }

    /**
     * Check if a given request has access to get item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_item_permissions_check( $request ) {
        //return true; <--use to make readable by all
        return current_user_can( Capabilities::GET_ITEM );
    }

    /**
     * Check if a given request has access to create items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function create_item_permissions_check( $request ) {
        return current_user_can( Capabilities::CREATE_ITEM );
    }

    /**
     * Check if a given request has access to update a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update_item_permissions_check( $request ) {
        return current_user_can( Capabilities::UPDATE_ITEM );
    }

    /**
     * Check if a given request has access to delete a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete_item_permissions_check( $request ) {
        return current_user_can( Capabilities::DELETE_ITEM );
    }
}
