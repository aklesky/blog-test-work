<?php

namespace App\Code;

class Database extends Object
{

    private $_pdoClass;

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
        $this->_pdoClass = new \PDO("{$driver}:dbname={$database};host={$hostName}",
            $userName, $userPass,
            array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            )
        );
    }

    public function getAdapter()
    {
        return $this->_pdoClass;
    }
}