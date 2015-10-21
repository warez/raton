<?php

global $raton_main_dir;

require_once( $raton_main_dir . "rest/item/ItemRestService.php");
require_once( $raton_main_dir . "Capabilities.php");

class ItemRestController extends WP_REST_Controller {

    private $namespace, $version, $base = "item";

    function __construct($version) {

        $this->$version = $version;
        $this->namespace = 'raton/v' . $this->$version;

        $this->ratonService = new ItemRestService($this);
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->base, array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->ratonService, 'get_items' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'            => array(

                ),
            ),
            array(
                'methods'         => WP_REST_Server::CREATABLE,
                'callback'        => array( $this->ratonService, 'create_item' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'            => $this->get_endpoint_args_for_item_schema( true ),
            ),
        ) );
        register_rest_route( $this->namespace, '/' . $this->base . '/(?P<id>[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->ratonService, 'get_item' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    ),
                ),
            ),
            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this->ratonService, 'update_item' ),
                'permission_callback' => array( $this, 'update_item_permissions_check' ),
                'args'            => $this->get_endpoint_args_for_item_schema( false ),
            ),
            array(
                'methods'  => WP_REST_Server::DELETABLE,
                'callback' => array( $this->ratonService, 'delete_item' ),
                'permission_callback' => array( $this, 'delete_item_permissions_check' ),
                'args'     => array(
                    'force'    => array(
                        'default'      => false,
                    ),
                ),
            ),
        ) );
        register_rest_route( $this->namespace, '/' . $this->base . '/schema', array(
            'methods'         => WP_REST_Server::READABLE,
            'callback'        => array( $this, 'get_public_item_schema' ),
        ) );
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
        //return true; <--use to make readable by all
        return current_user_can( Capabilities::GET_ITEMS );
    }

    /**
     * Check if a given request has access to get a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_item_permissions_check( $request ) {
        return $this->get_items_permissions_check( $request );
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

    /**
     * Prepare the item for create or update operation
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_item
     */
    protected function prepare_item_for_database( $request ) {
        return array();
    }

    /**
     * Prepare the item for the REST response
     *
     * @param mixed $item WordPress representation of the item.
     * @param WP_REST_Request $request Request object.
     * @return mixed
     */
    public function prepare_item_for_response( $item, $request ) {
        return array();
    }

    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params() {
        return array(
            'page'                   => array(
                'description'        => 'Current page of the collection.',
                'type'               => 'integer',
                'default'            => 1,
                'sanitize_callback'  => 'absint',
            ),
            'per_page'               => array(
                'description'        => 'Maximum number of items to be returned in result set.',
                'type'               => 'integer',
                'default'            => 10,
                'sanitize_callback'  => 'absint',
            ),
            'search'                 => array(
                'description'        => 'Limit results to those matching a string.',
                'type'               => 'string',
                'sanitize_callback'  => 'sanitize_text_field',
            ),
        );
    }
}
