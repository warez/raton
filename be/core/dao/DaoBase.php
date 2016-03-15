<?php

abstract class DaoBase {

    protected $tableName, $idName;

    function __construct($table, $idName) {

        global $wpdb;

        $this -> tableName = $wpdb->prefix . $table;
        $this -> idName = $idName;
    }

    function testIdPresent($data) {

        if(!array_key_exists($this->idName,$data) || $data[$this->idName] == null)
            throw new Exception($this->tableName . " " . $this->idName . " is null!");

        $id = $data[$this->idName];
        $item = $this->get($id);

        if(is_object($item) && get_class($item) == "WP_Error")
            throw new Exception("Item with id $id not exist.");

        return null;
    }

    function testObjectPresentInCategory($id) {

        global $wpdb;

        $cond = "id_category = " . $id;

        $query = " SELECT count(id) FROM " . $this->tableName . " WHERE " . $cond;
        $retCount = $wpdb->get_var($query);

        if($retCount > 0) {

            throw new Exception('Object exist in one of category with id: ' . $id . ". Categories not deleted.");

        }

        return;
    }

    function testCategoryPresent($data) {

        if(!array_key_exists("id_category",$data) || $data["id_category"] == null)
            throw new Exception("Category is null!");

        $idCategory = $data["id_category"];
        $categoryDao = new CategoryDao();
        $cat = $categoryDao->get($idCategory);

        if(is_object($cat) && get_class($cat) == "WP_Error")
            throw new Exception("Category with id: " . $idCategory . " not exist.");

        return;

    }

    function create($data, $format) {

        global $wpdb;

        try {

            ob_start();

            $ret = $wpdb->insert(
                $this->tableName,
                $data,
                $format
            );

            if ($ret == false) {
                throw new Exception("Insert error");
            }

            if (count($ret) != 1) {
                throw new Exception("Insert error");
            }

            $id = $wpdb->insert_id;
            return $this->get($id);

        } catch(Exception $e) {

            return new WP_Error( "create_" + $this->tableName , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

    function get($id) {

        global $wpdb;

        try {

            ob_start();

            $query = $wpdb->prepare(
                " SELECT * FROM " . $this->tableName .
                " WHERE " . $this->idName . " = %d ", $id);

            $result = $wpdb->get_row($query, OBJECT);

            if ($result == null) {
                throw new Exception('Null result found for id: ' . $id);
            }

            if (count($result) != 1) {
                throw new Exception('No unique result for id: ' . $id);
            }

            return $result;

        } catch(Exception $e) {

            return new WP_Error( "get_" + $this->tableName, __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

    function delete( $id ) {

        try {

            global $wpdb;
            ob_start();

            $cond = $this->idName . " = " . $id;
            $query = " DELETE FROM " . $this->tableName . " WHERE " . $cond;
            $retCount = $wpdb->query($query);

            if($retCount != 1) {

                throw new Exception('Delete error for item number id: ' . $id);

            }

            return new WP_REST_Response();

        } catch(Exception $e) {

            throw new Exception($e->getMessage());

        } finally {
            ob_end_clean();
        }

    }

    function update($data, $format) {

        global $wpdb;

        try {

            $ret = $wpdb->update(
                $this->tableName,
                $data,
                array( $this -> idName => $data[ $this -> idName ] ),
                $format,
                array( '%d' )
            );

            if($ret == false) {

                throw new Exception('Update error for id: ' . $data[ $this -> idName ]);

            }

            $id = $data[ $this->idName ];
            return $this->get($id);

        } catch(Exception $e) {

            return new WP_Error( "update_" +  $this->tableName , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

}


