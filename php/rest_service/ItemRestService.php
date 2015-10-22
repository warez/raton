<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["MODEL"] . "Item.php");
require_once($raton_dir["SERVICE"] . "BaseRestService.php");

class ItemRestService extends BaseRestService {

    function __construct($restController)
    {
        parent :: __construct($restController);
    }

    function prepareForDb($item) {
        return $item;
    }

    function prepareForResponse($item, $request) {
        return $item;
    }

    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function getAll( $request ) {

        $items = array();
        $items[] = new Item("Prova 1");
        $items[] = new Item("Prova 2");
        $items[] = new Item("Prova 3");

        $data = $this -> prepareCollectionForResponse($items);
        return new WP_REST_Response( $data, 200 );
    }

    /**
     * Get one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get( $request ) {

        $item = new Item("Prova 3");
        $item = $this->prepareForResponse($item, $request);

        return new WP_REST_Response( $item, 200 );
        //return new WP_Error( 'cant-create', __( 'message', 'text-domain'), array( 'status' => 500 ) );
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