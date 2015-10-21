<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_main_dir;

class ItemRestService {

    private $restController = null;

    function __construct($restController) {
        $this->restController = $restController;
    }

    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items( $request ) {
        $items = array(); //do a query, call another class, etc
        $data = array();
        foreach( $items as $item ) {
            $itemdata = $this->restController->prepare_item_for_response( $item, $request );
            $data[] = $this->restController->prepare_response_for_collection( $itemdata );
        }

        return new WP_REST_Response( $data, 200 );
    }

    /**
     * Get one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_item( $request ) {
        //get parameters from request
        $params = $request->get_params();
        $item = array();//do a query, call another class, etc
        $data = $this->restController->prepare_item_for_response( $item, $request );

        //return a response or error based on some conditional
        if ( 1 == 1 ) {
            return new WP_REST_Response( $data, 200 );
        }else{
            return new WP_Error( 'code', __( 'message', 'text-domain' ) );
        }
    }

    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function create_item( $request ) {

        $item = $this->restController->prepare_item_for_database( $request );

        if ( function_exists( 'slug_some_function_to_create_item')  ) {
            $data = slug_some_function_to_create_item( $item );
            if ( is_array( $data ) ) {
                return new WP_REST_Response( $data, 200 );
            }
        }

        return new WP_Error( 'cant-create', __( 'message', 'text-domain'), array( 'status' => 500 ) );


    }

    /**
     * Update one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function update_item( $request ) {
        $item = $this->restController->prepare_item_for_database( $request );

        if ( function_exists( 'slug_some_function_to_update_item')  ) {
            $data = slug_some_function_to_update_item( $item );
            if ( is_array( $data ) ) {
                return new WP_REST_Response( $data, 200 );
            }
        }

        return new WP_Error( 'cant-update', __( 'message', 'text-domain'), array( 'status' => 500 ) );

    }

    /**
     * Delete one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function delete_item( $request ) {
        $item = $this->restController->prepare_item_for_database( $request );

        if ( function_exists( 'slug_some_function_to_delete_item')  ) {
            $deleted = slug_some_function_to_delete_item( $item );
            if (  $deleted  ) {
                return new WP_REST_Response( true, 200 );
            }
        }

        return new WP_Error( 'cant-delete', __( 'message', 'text-domain'), array( 'status' => 500 ) );
    }

}