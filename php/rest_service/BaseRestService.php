<?php

class BaseRestService
{

    protected $restController = null;

    function __construct($restController)
    {
        $this->restController = $restController;
    }

    function prepareCollectionForResponse($items, $request)
    {

        $ret = array();

        foreach ($items as $item) {
            $ret[] = $this->prepareForResponse($item, $request);
        }

        return ret;
    }

    function prepareForDb($item)
    {
        return $item;
    }

    function prepareForResponse($item, $request)
    {
        return $item;
    }

    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function getAll( $request ) {
        return new WP_Error( 'cant-getAll', __( 'message', 'text-domain'), array( 'status' => 500 ) );
    }

    /**
     * Get one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get( $request ) {
        return new WP_Error( 'cant-get', __( 'message', 'text-domain'), array( 'status' => 500 ) );
    }

    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function create( $request ) {
        return new WP_Error( 'cant-create', __( 'message', 'text-domain'), array( 'status' => 500 ) );
    }

    /**
     * Update one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function update( $request ) {
        return new WP_Error( 'cant-update', __( 'message', 'text-domain'), array( 'status' => 500 ) );
    }

    /**
     * Delete one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function delete( $request ) {
        return new WP_Error( 'cant-delete', __( 'message', 'text-domain'), array( 'status' => 500 ) );
    }
}