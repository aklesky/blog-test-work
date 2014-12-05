<?php
namespace App;

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

use App\Code\App;

define('DS', DIRECTORY_SEPARATOR);

define('App', dirname(__FILE__) . DS);
define('AppCode', App . 'code' . DS);

define('ControllersNameSpace', '\\App\\Code\\Controllers\\');

define('ModelsNameSpace', '\\App\\Code\\Models\\');

spl_autoload_extensions(".php");
spl_autoload_register();

session_start();

App::run(
    AppCode . 'controllers',
    AppCode . 'models',
    App . 'views',
    require_once 'config.php'
);
?>