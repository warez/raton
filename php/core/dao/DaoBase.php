<?php

abstract class DaoBase {

    protected $tableName, $idName;

    function __construct($table, $idName) {

        global $wpdb;

        $this -> tableName = $wpdb->prefix . $table;
        $this -> idName = $idName;
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
                throw new Exception("Insert error for table: " . $this->tableName);
            }

            if (count($ret) != 1) {
                throw new Exception("Insert error for table: " . $this->tableName);
            }

            $id = $wpdb->insert_id;
            return $this->get($id);

        } catch(Exception $e) {

            return new WP_Error( "Core error" , __( $e->getMessage() ), array( 'status' => 500 ) );

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
                throw new Exception('Null result found for id: ' . $id . " in table: " . $this->tableName);
            }

            if (count($result) != 1) {
                throw new Exception('No unique result for id: ' . $id . " in table: " . $this->tableName);
            }

            return $result;

        } catch(Exception $e) {

            return new WP_Error( "Core error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

    function delete( $idListOrId = array() ) {

        try {

            global $wpdb;
            ob_start();

            if(is_array($idListOrId) ) {

                if(count($idListOrId) == 0)
                    return;

                $ids = join(',', $idListOrId);
                $cond = $this->idName . " in " . join(',', $idListOrId);
                $num = count($idListOrId);

            } else {

                $ids = $idListOrId;
                $cond = $this->idName . " = " . $idListOrId;
                $num = 1;
            }

            $query = " DELETE FROM " . $this->tableName . " WHERE " . $cond;
            $retCount = $wpdb->query($query);

            if($retCount != $num) {

                throw new Exception('Delete error for item number ids: ' . $ids . " in table: " . $this->tableName);

            }

            return true;

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

                throw new Exception('Update error for id: ' . $data[ $this -> idName ] . " in table: " . $this->tableName);

            }

            $id = $data[ $this->idName ];
            return $this->get($id);

        } catch(Exception $e) {

            return new WP_Error( "Core error" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }

    }

}


