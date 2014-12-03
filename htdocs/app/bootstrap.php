<?php
namespace App;

use App\Code\App;

define('AppCode', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'code' . DIRECTORY_SEPARATOR);

define('AppControllers', AppCode . 'controllers');

define('AppModels', AppCode . 'models');


spl_autoload_extensions(".php");
spl_autoload_register();
echo "<pre>";
App::run();
?>