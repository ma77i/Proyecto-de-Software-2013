<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('APP_PATH', ROOT . 'application' . DS);
define ('LIB_PATH', ROOT. 'libs'. DS);
require_once LIB_PATH . 'Twig'. DS . 'Autoloader.php';
require_once APP_PATH . 'Config.php';
require_once APP_PATH . 'Request.php';
require_once APP_PATH . 'Bootstrap.php';
require_once APP_PATH . 'Database.php';
require_once APP_PATH . 'Controller.php';
require_once APP_PATH . 'Model.php';
require_once APP_PATH . 'View.php';
require_once APP_PATH . 'Session.php';
Session::start();
try{
    Bootstrap::run(new Request);
}
catch(Exception $e){
    echo $e->getMessage();
}

?>
