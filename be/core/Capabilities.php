<?php

global $raton_main_dir;

class Capabilities {

    const CREATE_ITEM = "create_item_permissions_check";
    const GET_ITEM = "get_item_permissions_check";
    const UPDATE_ITEM = "update_item_permissions_check";
    const DELETE_ITEM = "delete_item_permissions_check";

    private static function getAllCap() {
        return array(
            Capabilities::CREATE_ITEM,
            Capabilities::GET_ITEM,
            Capabilities::UPDATE_ITEM,
            Capabilities::DELETE_ITEM,
        );
    }

    public static function addCapabilites() {

        global $wp_roles;
        $grant = true;
        $allCap = Capabilities::getAllCap();

        foreach ( $wp_roles->role_objects as $id => $roleObj ) {

            foreach ( $allCap as $cap ){

                if (!$roleObj->has_cap( $cap ))
                    $roleObj->add_cap($cap, $grant);

            }

        }
    }

    public static function removeCapabilites() {

        global $wp_roles;
        $allCap = Capabilities::getAllCap();

        foreach ( $wp_roles->role_objects as $id => $roleObj ) {

            foreach ( $allCap as $cap ){

                if ($roleObj->has_cap( $cap ))
                    $roleObj->remove_cap($cap);

            }

        }

    }
}