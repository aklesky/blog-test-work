<?php
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR
    . dirname(__FILE__)
    . PATH_SEPARATOR .
    dirname(__FILE__) . '/../../htdocs/' . PATH_SEPARATOR . dirname(__FILE__));

require_once 'app/bootstrap.php';