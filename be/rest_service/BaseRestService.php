<?php

abstract class BaseRestService
{

    public static $DEFAULT_PAGE_SIZE = 10;
    public static $DEFAULT_PAGE = 0;

    protected $dao = null;

    function __construct($dao)
    {
        $this->dao = $dao;

        if($this->dao == null)
            throw new Exception( 'Dao is null in ' . get_class() );
    }

    function setProp($key, & $obj, $value) {
        if($obj == null || $key == null)
            return;

        if(!is_array($obj))
            $obj -> { $key } = $value;
        else
            $obj [ $key ] = $value;
    }

    function getProp($key, $obj) {
        if($obj == null || $key == null)
            return null;

        if(!is_array($obj))
            $array = get_object_vars($obj);
        else
            $array = $obj;

        if(array_key_exists($key, $array))
            return $array[$key];

        return null;
    }

    function prepareForDb($item, $op)
    {
        return $item;
    }

    function prepareForResponse($item, $op)
    {
        return $item;
    }

    function getIdFromRequest($request) {

        $idStr = $request->get_param("id");
        return intval($idStr);

    }

    function getDataFormat($data) {
        $format = $this->getFormat();
        $ret = array();

        foreach ( $data as $prop => $val) {
            $ret[$prop] = $format[$prop];
        }

        return $ret;
    }

    function getFormat() {
        return new WP_Error( "get_format_" + get_class() , get_class() . '::getFormat not implemented', array( 'status' => 500 ) );
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

            $itemOrError = $this->prepareForResponse($itemOrError, "GET");
            return $itemOrError;

        } catch (Exception $e) {

            return new WP_Error( "get_" + get_class() , __( $e->getMessage() ), array( 'status' => 500 ) );

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

            ob_start();

            $jsonItem = $request->get_json_params();
            $item = $this->prepareForDb($jsonItem, "CREATE");

            $format = $this->getDataFormat($item);

            $itemOrError = $this->dao->create($item, $format);
            if (get_class($itemOrError) == "WP_Error")
                return $itemOrError;

            $itemOrError = $this->prepareForResponse($itemOrError, "CREATE");
            return $itemOrError;

        } catch (Exception $e) {
            die();
            return new WP_Error( "create_" + get_class(), __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_end_clean();
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

            $jsonItem = $request->get_json_params();
            $item = $this->prepareForDb($jsonItem, "UPDATE");

            $format = $this->getDataFormat($item);

            $boolOrError = $this->dao->update($item, $format);
            if(get_class($boolOrError) == "WP_Error")
                return $boolOrError;

            $itemOrError = $this->prepareForResponse($boolOrError, "UPDATE");
            return $itemOrError;

        } catch (Exception $e) {

            return new WP_Error( "update_" + get_class() , __( $e->getMessage() ), array( 'status' => 500 ) );

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

            if(is_object($ret) && get_class($ret) == "WP_Error")
                return $ret;

            if(is_bool($ret) && !$ret)
                throw new Exception("No item deleted");

            return new WP_REST_Response( array() , 200 );

        } catch (Exception $e) {

            return new WP_Error( "delete_" + get_class() , __( $e->getMessage() ), array( 'status' => 500 ) );

        }
    }
}