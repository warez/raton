<?php

global $raton_dir;
require_once($raton_dir["DAO"] . "DaoBase.php");

class VoteDao extends DaoBase
{

    function __construct()
    {

        parent::__construct("votes", "id");
    }

    function update($data, $format)
    {

        try {

            parent::testIdPresent($data);
            return parent::update($data, $format);

        } catch (Exception $e) {

            return new WP_Error("update_vote", __($e->getMessage()), array('status' => 500));

        }

    }

}