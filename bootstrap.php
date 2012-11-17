<?php
namespace Acorn;
/**
 * <p>Main routine for the Acorn framework.</p>
 * <p>This code sets up reference to the directories, configures a basic
 * autoloader, analyses the incoming request data, and routes the resopnse
 * and any errors that occur.</p>
 */

// Path configuration
define('TOP_PATH', realpath(dirname(__FILE__) . '/..') . DIRECTORY_SEPARATOR);
define('SYSTEM_PATH',  TOP_PATH    . 'acorn'       . DIRECTORY_SEPARATOR);
define('PROJECT_PATH', TOP_PATH    . PROJECT_NAME  . DIRECTORY_SEPARATOR);

define('WWW_PATH', trim($_SERVER['HTTP_HOST'] . getenv('PUBLIC_PATH'), '/'));

define('DEBUG', false !== getenv('DEBUG'));

// Load the Main utility class
require(SYSTEM_PATH . 'Acorn.php');

// Configure the class auto-loader
Acorn::addClassPath(SYSTEM_PATH . '%c.php', '\Acorn\\');
Acorn::addClassPath(SYSTEM_PATH . 'stubs/%c.php', '\Acorn\\');
spl_autoload_register('\Acorn\Acorn::loadClass');

// Get the requested URL
if (false === empty($_SERVER['PATH_INFO']))
	$url = $_SERVER['PATH_INFO'];
else
	$url = '/';

// Load the request object
\Acorn\Request::construct($url, $_POST, $_GET);

// Load the project's site configuration
require_once(PROJECT_PATH . 'site.php');

// Get the controller / method for this URL
$route = \Acorn\Request::route();
$controller = new $route->controller();

if (false === ($controller instanceof \Acorn\Controller))
	die('Supplied controller is not instance of Acorn\'s base controller');

// Fatal Error (Shutdown) Handler
$errHandler = function() use($controller)
{
	$err = error_get_last();
	if (null !== $err)
		$controller->handleError($err);
};
register_shutdown_function($errHandler);

// Error handler
$errHandler = function($errno, $errstr, $errfile, $errline) use($controller)
{
	$err = array(
		'type' => $errno,
		'message' => $errstr,
		'file' => $errfile,
		'line' => $errline
	);
	$controller->handleError($err);
};
set_error_handler($errHandler);
ini_set('display_errors', 0);

// Excpetion Handler
set_exception_handler(array(&$controller, 'handleException'));

// Run the action requested
$controller->before();
$controller->{$route->method}();
$controller->after();
