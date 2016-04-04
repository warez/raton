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

        $votes = parent::getProp("votes", $filter);
        if($votes != null) {
            parent::setProp("votes", $filter, $votes);
        }

        return $filter;
    }

    function search($request) {
        try {

            return new WP_REST_Response();

        } catch (Exception $e) {

            return new WP_Error( "get_cat_vote_type" , __( $e->getMessage() ), array( 'status' => 500 ) );

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