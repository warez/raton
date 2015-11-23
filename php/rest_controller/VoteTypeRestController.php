<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "VoteTypeRestService.php");

class VoteTypeRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new VoteTypeRestService( $this );
        $this->base = "voteType";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/search/byCategory/(?P<categoryId>[-]?[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'searchVoteTypeByCategory' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'            => array(
                    'context'          => array(
                        'default'      => 'view',
                    )
                )
            ),
        ) );

    }

    public function get_item_schema() {

        $schema = array(
            'title'      => 'voteType',
            'type'       => 'object',
            'properties' => array(

                'title'       => array(
                    'description' => 'The vote type title.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'required'    => true,
                ),
                'description' => array(
                    'description' => 'Vote type description.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'position' => array(
                    'description' => 'Vote type position.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'vote_limit' => array(
                    'description' => 'Max number of vote star.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'id_category'  => array(
                    'description' => 'Category id.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                ),
                'id'          => array(
                    'description' => 'Unique identifier for vote type.',
                    'type'        => 'integer',
                    'context'     => array( 'view'),
                    'readonly'    => true,
                )
            ),
        );
        return $this->add_additional_fields_schema( $schema );
    }

}
