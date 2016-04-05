<?php
/**
 * Created by PhpStorm.
 * User: warez
 * Date: 17/10/15
 * Time: 19.33
 */

global $raton_dir;
require_once($raton_dir["SERVICE"] . "BaseRestService.php");
require_once($raton_dir["SERVICE"] . "VoteRestService.php");
require_once( $raton_dir["DAO"] . "ReviewDao.php");

class ReviewRestService extends BaseRestService {

    private $voteRestService;

    private $format = array(
        "id" => "%d",
        "review" => "%s",
        "id_item" => "%d",
        "insert_date" => "%d",
        "id_user" => "%d"
    );

    function __construct()
    {
        parent :: __construct(new ReviewDao());
        $this->voteRestService = new VoteRestService();
    }

    function prepareForDb($filter) {

        $id = parent::getProp("id", $filter);
        if($id != null) {
            parent::setProp("id", $filter, $id);
        }

        $review = parent::getProp("review", $filter);
        if($review != null) {
            parent::setProp("review", $filter, $review);
        }

        $id_item = parent::getProp("id_item", $filter);
        if($id_item != null) {
            parent::setProp("id_item", $filter, $id_item);
        }

        $id_user = parent::getProp("id_user", $filter);
        if($id_user != null) {
            parent::setProp("id_user", $filter, $id_user);
        }

        $insert_date = parent::getProp("insert_date", $filter);
        if($insert_date != null) {
            parent::setProp("insert_date", $filter, $insert_date);
        }

        $votes = parent::getProp("votes", $filter);
        if($votes != null) {
            parent::setProp("votes", $filter, $votes);
        }

        return $filter;
    }

    function search($request) {
        global $wpdb;

        try {

            $dao = $this->dao;
            $tableName = $dao->getTableName();

            $page = $request->get_param("page");
            $itemPerPage = $request->get_param("per_page");;
            $itemId = $request->get_param("idItem");

            $page = $page - 1;
            $firstItem = $page * $itemPerPage;

            $params = array($itemId);
            $queryCount = $wpdb->prepare(" SELECT count(*) FROM " . $tableName . " where id_item = %d", $params);
            $retCount = $wpdb->get_var($queryCount);

            $data = array("votes"=> array(), "items"=> array(), "total_count"=>$retCount , "page"=>$page + 1 , "itemPerPage"=>$itemPerPage);
            if($retCount == 0) {
                return new WP_REST_Response($data);
            }

            $params = array($firstItem,$itemPerPage);
            $query = $wpdb->prepare(
                " SELECT * FROM " . $tableName . " order by insert_date desc LIMIT %d,%d", $params);
            $result = $wpdb->get_results($query, OBJECT);
            if ($result == null) {
                return new WP_REST_Response($data);
            } else {
                $data["items"] = $result;
            }

            $ids = array_map(create_function('$o', 'return $o->id_item;'), $result);
            $comma_separated = implode(",", $ids);
            $query = $wpdb->prepare(
                "SELECT r.id_user, r.id as 'review_id', v.id_vote_types, v.vote_value
                FROM wp_reviews AS r
                LEFT JOIN wp_review_votes AS rv ON r.id = rv.id_review
                LEFT JOIN wp_votes AS v ON v.id = rv.id_vote
                where r.id_item in (" . $comma_separated .") GROUP BY r.id_item, v.id_vote_types, r.id_user");
            $result = $wpdb->get_results($query, OBJECT);
            if ($result == null) {
                throw new Exception();
            } else {
                $data["votes"] = $result;
            }

            return new WP_REST_Response($data);

        } catch(Exception $e) {

            return new WP_Error( "search_review" , __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_clean();
        }
    }

    public function create( $request ) {

        try {

            ob_start();
            $this->dao->startTransaction();

            $jsonItem = $request->get_json_params();
            $item = $this->prepareForDb($jsonItem);

            $format = $this->getFormat($item);

            $itemOrError = $this->dao->create($item, $format);
            if (get_class($itemOrError) == "WP_Error") {
                $this->dao->rollback();
                return $itemOrError;
            }

            foreach ($item->votes as $voteData) {
                $voteOrError =
                    $this->voteRestService->addReviewVote($item, $item->votes);
                if (get_class($voteOrError) == "WP_Error") {
                    $this->dao->rollback();
                    return $voteOrError;
                }

            }

            $itemOrError = $this->prepareForResponse($itemOrError, $request);
            $this->dao->commit();
            return $itemOrError;

        } catch (Exception $e) {
            die();
            return new WP_Error( "create_" + get_class(), __( $e->getMessage() ), array( 'status' => 500 ) );

        } finally {
            ob_end_clean();
        }
    }

    function prepareForResponse($filter, $request) {

        return $this->prepareForDb($filter);
    }

    function getFormat($data) {
        $format = array();
        foreach ( $data as $d => $a) {
            $format[$d] = $this->format[$d];
        }
        return $format;
    }
}