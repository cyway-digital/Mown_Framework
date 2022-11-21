<?php

/**
 * Mown Framework 3.0
 * Cyway Digital - 2022
 *
 * @author M. Vulcano
 * @link https://cyway.it
 * @location Italy
 *
 */

// Load Configuration files
require 'conf/config.php';
require 'conf/database.php';
require 'conf/mail.php';
require 'libs/MownException.php';

$permittedIPs = [
    '94.36.192.133'
];

// Handle Maintenance mode
if (MAINTENANCE_MODE && !in_array($_SERVER['REMOTE_ADDR'],$permittedIPs)) {
    http_response_code(503); // Service Unavailable
    echo "<h1>SISTEMA IN MANUTENZIONE - torna più tardi!</h1>";
    exit;
}

// Define Error reporting settings
if (defined('SYS_STAGE') && SYS_STAGE != 'PROD') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Define autoloader function
function autoloader($class) {

    try {
        $package = array('MVC','static');
        $path = LIBS_PATH . $class .".php";

        if (file_exists($path)) {
            require_once $path;
            return true;
        } else {
            foreach ($package as $dir) {
                $path = LIBS_PATH . $dir . "/" . $class .".php";
                if (file_exists($path)) {
                    require_once $path;
                    return true;
                }
            }
        }

        throw new mownException("Autoloader: Unable to load $class class.");

    } catch (mownException $e) {
        $e->errorMessage();
    }
}

// Set Uncaugth Exception Handler
set_exception_handler(array('mownException', 'unhandledException'));

// Set autoloader
spl_autoload_register('autoloader');

// Check PHP Compatibility
try{
    if ( !version_compare(phpversion(), SYS_PHPVER, '>') ) {
        throw new mownException("ERROR: System check not passed. Use PHP >= ".SYS_PHPVER);
    }
} catch (mownException $e) {
    $e->errorMessage();
}

// Invoke route engine
try {
    if (class_exists('Routes')) {
        $routes = new Routes();
    } else {
        throw new mownException('Class "Routes" not callable in '.basename(__FILE__).' on line'.__LINE__);
    }
} catch (mownException $e) {
    $e->errorMessage();
}

// set Optional Path Settings
//$routes->setControllerPath();
//$routes->setModelPath();
//$routes->setDefaultFile();
//$routes->setErrorFile();

// Start the session
Session::init();

// Let magic starts!
$routes->init();