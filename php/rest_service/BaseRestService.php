<?php

abstract class BaseRestService
{
    protected $dao = null;
    protected $restController = null;

    function __construct($restController, $dao)
    {
        $this->restController = $restController;
        $this->dao = $dao;

        if($this->dao == null)
            throw new Exception( 'Dao is null in ' . get_class() );
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

    function getIdFromRequest($request) {

        $idStr = $request->get_param("id");
        return intval($idStr);

    }

    function getFormat() {
        return new WP_Error( get_class() . '::getFormat not implemented', __( 'message', 'text-domain'), array( 'status' => 500 ) );
    }

    /**
     * Get one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|Item
     */
    public function get( $request ) {

        try {

            $id = $this->getIdFromRequest($request);

            $itemOrError = $this->dao->get($id);
            if (get_class($itemOrError) == "WP_Error")
                return $itemOrError;

            $itemOrError = $this->prepareForResponse($itemOrError, $request);
            return $itemOrError;

        } catch (Exception $e) {

            return new WP_Error( "Get error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|Item
     */
    public function create( $request ) {

        try {

            $jsonItem = $request->get_json_params();
            $item = $this->prepareForDb($jsonItem);

            $format = $this->getFormat();

            $itemOrError = $this->dao->create($item, $format);
            if (get_class($itemOrError) == "WP_Error")
                return $itemOrError;

            $itemOrError = $this->prepareForResponse($itemOrError, $request);
            return $itemOrError;

        } catch (Exception $e) {

            return new WP_Error( "Create error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }

    /**
     * Update one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update( $request ) {

        try {

            $item = $this->prepareForDb($request);
            $format = $this->getFormat();

            $boolOrError = $this->dao->update($item, $format);
            if(get_class($boolOrError) == "WP_Error")
                return $boolOrError;

            return $boolOrError;

        } catch (Exception $e) {

            return new WP_Error( "Update error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }

    }

    /**
     * Delete one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete( $request ) {

        try {

            $id = $this->getIdFromRequest($request);

            $ret = $this->dao->delete($id);
            return $ret;

        } catch (Exception $e) {

            return new WP_Error( "Delete error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }
}