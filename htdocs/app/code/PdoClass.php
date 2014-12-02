<?php

namespace App\Code;

class PdoClass extends \PDO
{
    /**
     * @param null $hostName
     * @param null $database
     * @param null $driver
     * @param null $userName
     * @param null $userPass
     */
    function __construct($hostName = null, $database = null,
                         $driver = null, $userName = null,
                         $userPass = null)
    {

        parent::__construct("{$driver}:dbname={$database};host={$hostName}", $userName, $userPass,
            array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            )
        );
    }
} 