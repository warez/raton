<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "CategoryRestService.php");

class CategoryRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new CategoryRestService( $this );
        $this->base = "category";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/tree/(?P<from>[-]?[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'getCategoryTree' ),
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
            'title'      => 'category',
            'type'       => 'object',
            'properties' => array(

                'description' => array(
                    'description' => 'The category description.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' )
                ),
                'title'       => array(
                    'description' => 'The category title.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'required'    => true,
                ),
                'id_parent_category' => array(
                    'description' => 'Parent category id.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'is_main_category'  => array(
                    'description' => 'If category is main or sub-category.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                ),
                'id'          => array(
                    'description' => 'Unique identifier for the category.',
                    'type'        => 'integer',
                    'context'     => array( 'view'),
                    'readonly'    => true,
                )
            ),
        );
        return $this->add_additional_fields_schema( $schema );
    }

}
