<?php
namespace App;

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

use App\Code\App;

define('DS', DIRECTORY_SEPARATOR);

define('App', dirname(__FILE__) . DS);
define('AppCode', App . 'code' . DS);

define('Uploads', dirname(dirname(__FILE__)) . DS . 'htdocs/uploads' . DS);

define('Chunks', dirname(dirname(__FILE__)) . DS . 'htdocs/chunk' . DS);

spl_autoload_extensions(".php");

spl_autoload_register(function ($className) {
    spl_autoload(
        mb_strtolower(
            dirname(dirname(__FILE__)) . DS . str_replace("\\", DS, $className)
        )
    );
});

session_start();

App::run(
    AppCode . 'controllers',
    AppCode . 'models',
    App . 'views',
    require_once 'config.php'
);


?>