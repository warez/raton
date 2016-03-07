<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "FilterRestService.php");

class FilterRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new FilterRestService( $this );
        $this->base = "filter";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/search/byCategory/(?P<categoryId>[-]?[\d]+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'searchFilterByCategory' ),
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
            'title'      => 'filter',
            'type'       => 'object',
            'properties' => array(

                'title'       => array(
                    'description' => 'The filter title.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'required'    => true,
                ),
                'description' => array(
                    'description' => 'Filter description.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'position' => array(
                    'description' => 'Filter position.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'mandatory' => array(
                    'description' => 'Filter mandatori, 0 or 1.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'id_type'  => array(
                    'description' => 'Filter type id.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                ),
                'id_category'  => array(
                    'description' => 'Category id.',
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                ),
                'id'          => array(
                    'description' => 'Unique identifier for the item.',
                    'type'        => 'integer',
                    'context'     => array( 'view'),
                    'readonly'    => true,
                )
            ),
        );
        return $this->add_additional_fields_schema( $schema );
    }

}
