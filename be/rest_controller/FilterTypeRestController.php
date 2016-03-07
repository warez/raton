<?php

global $raton_dir;

require_once( $raton_dir["CORE"] . "Capabilities.php");
require_once( $raton_dir["CONTROLLER"] . "BaseRestController.php");
require_once( $raton_dir["SERVICE"] . "FilterTypeRestService.php");

class FilterTypeRestController extends BaseRestController {

    function __construct($version) {

        parent::__construct($version);

        $this->service = new FilterTypeRestService( $this );
        $this->base = "filterType";
    }

    public function register_routes() {

        parent::register_routes();

        register_rest_route( $this->namespace, '/' . $this->base . '/search/(?P<searchTitle>.+)', array(
            array(
                'methods'         => WP_REST_Server::READABLE,
                'callback'        => array( $this->service, 'searchFilterTypeByTitle' ),
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
            'title'      => 'filterType',
            'type'       => 'object',
            'properties' => array(

                'title'       => array(
                    'description' => 'The filter type title.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'required'    => true,
                ),
                'filter_args' => array(
                    'description' => 'Filter args in json format.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'meta_type'  => array(
                    'description' => 'Filter type meta type.',
                    'type'        => 'string',
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
