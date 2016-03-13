<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "ItemRestService.php");

class ItemRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new ItemRestService( $this );
        $this->base = "item";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/search', array(
            array(
                'methods'         => WP_REST_Server::EDITABLE,
                'callback'        => array( $this->service, 'search' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => $this->getSearchParams()
            )
        ) );

    }

    public function getSearchParams() {
        $ret = $this->get_collection_params();

        $ret["from"] = array(
            'description'        => __( 'category id to retrieve items.' ),
            'type'               => 'integer',
            'default'            => "-1",
            'sanitize_callback'  => 'absint'
        );

        $ret["title"] = array(
                'description'        => __( 'Title of item.' ),
                'type'               => 'string',
                'default'            => "",
                'validate_callback'  => 'rest_validate_request_arg'
        );

        $ret["description"] = array(
            'description'        => __( 'Description of item.' ),
            'type'               => 'string',
            'default'            => "",
            'validate_callback'  => 'rest_validate_request_arg'
        );

        $ret["request_approve_type"] = array(
            'description'        => __( 'Request approve flag of item.' ),
            'type'               => 'string',
            'default'            => "a",
            'validate_callback'  => 'rest_validate_request_arg'
        );

        $ret["approved_type"] = array(
            'description'        => __( 'Approve d type of item.' ),
            'type'               => 'string',
            'default'            => "a",
            'validate_callback'  => 'rest_validate_request_arg'
        );

        return $ret;
    }

    public function get_collection_params() {
        return array(
            'context'                => $this->get_context_param(),
            'page'                   => array(
                'description'        => __( 'Current page of the collection.' ),
                'type'               => 'integer',
                'default'            => 1,
                'sanitize_callback'  => 'absint',
                'validate_callback'  => 'rest_validate_request_arg',
                'minimum'            => 1,
            ),
            'per_page'               => array(
                'description'        => __( 'Maximum number of items to be returned in result set.' ),
                'type'               => 'integer',
                'default'            => 10,
                'minimum'            => 1,
                'maximum'            => 100,
                'sanitize_callback'  => 'absint',
                'validate_callback'  => 'rest_validate_request_arg',
            )
        );
    }
}
