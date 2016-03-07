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

    public function get_item_schema() {

        $schema = array(
            'title'      => 'item',
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
                'image' => array(
                    'description' => 'Image link.',
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'id_category'  => array(
                    'description' => 'Item category.',
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
