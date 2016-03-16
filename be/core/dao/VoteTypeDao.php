<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class VoteTypeDao extends DaoBase
{

    function __construct()
    {

        parent::__construct("votes_types", "id");
    }


    function update($data, $format)
    {

        try {

            parent::testIdPresent($data);
            parent::testCategoryPresent($data);

            $this->updatePositionFrom(
                $data["position"],
                $data["id_category"],
                "+", ">=" );

            $this->updatePositionFrom(
                $data["position"],
                $data["id_category"],
                "-", "<" );

            return parent::update($data, $format);

        } catch (Exception $e) {

            return new WP_Error("update_vote_type", __($e->getMessage()), array('status' => 500));

        }

    }

    function move($id, $idOther, $id_category, $mode)
    {

        global $wpdb;

        try {

            ob_start();

            $setCondMe = " SET position = position ";
            $setCondOther = " SET position = position ";

            if ($mode == "UP") {
                $setCondMe .= " - 1";
                $setCondOther .= " + 1";
            } else if ($mode == "DOWN") {
                $setCondMe .= " + 1";
                $setCondOther .= " - 1";
            }

            $params = array();
            $params[] = $id;
            $setQuery = $wpdb->prepare(" UPDATE " . $this->tableName . $setCondMe . " where id = %d", $params);
            $wpdb->query($setQuery);

            $params = array();
            $params[] = $idOther;
            $setQuery = $wpdb->prepare(" UPDATE " . $this->tableName . $setCondOther . " where id = %d", $params);
            $wpdb->query($setQuery);

            return $this->search($id_category);

        } catch (Exception $e) {

            return new WP_Error("move_vote_type", __($e->getMessage()), array('status' => 500));

        } finally {
            ob_clean();
        }

    }

    function search($from)
    {

        global $wpdb;

        try {

            ob_start();

            $whereCond = " where id_category = %d";
            $params = array();
            $params[] = $from;

            $queryCount = $wpdb->prepare(" SELECT count(*) FROM " . $this->tableName . $whereCond, $params);
            $retCount = $wpdb->get_var($queryCount);
            $data = array("items" => array(), "total_count" => $retCount);

            if ($retCount == 0) {
                return new WP_REST_Response($data);
            }

            $query = $wpdb->prepare(
                " SELECT * FROM " . $this->tableName .
                " " . $whereCond . " order by position asc", $params);

            $result = $wpdb->get_results($query, OBJECT);

            if ($result == null) {
                return new WP_REST_Response($data);
            }

            $data = array("items" => $result, "total_count" => $retCount);
            return new WP_REST_Response($data);

        } catch (Exception $e) {

            return new WP_Error("search_vote_type", __($e->getMessage()), array('status' => 500));

        } finally {
            ob_clean();
        }

    }

    function updatePositionFrom($position, $idCateogry, $operation, $operator) {

        global $wpdb;

        $params = array();
        $params[] = intval($idCateogry);
        $params[] = intval($position);
        $updateQuery = $wpdb->prepare(
            " UPDATE " . $this->tableName ." SET position = position ". $operation . " 1 where id_category = %d and position " . $operator ." %d ", $params);
        $wpdb->query($updateQuery);
    }

    function delete($id)
    {
        global $wpdb;

        try {

            ob_start();

            $params = array();
            $params[] = $id;
            $query = $wpdb->prepare(
                " SELECT position, id_category FROM " . $this->tableName ." where id = %d ", $params);
            $result = $wpdb->get_results($query, ARRAY_A);

            $this->updatePositionFrom(
                $result[0]["position"],
                $result[0]["id_category"],
                "-", ">" );

            return parent::delete($id);

        } catch (Exception $e) {

            return new WP_Error("delete_vote_type", __($e->getMessage()), array('status' => 500));

        }
    }


    function create($data, $format)
    {

        try {

            parent::testCategoryPresent($data);

            $this->updatePositionFrom(
                $data["position"],
                $data["id_category"],
                "+", ">=" );

            return parent::create($data, $format);

        } catch (Exception $e) {

            return new WP_Error("create_vote_type", __($e->getMessage()), array('status' => 500));

        }
    }

}