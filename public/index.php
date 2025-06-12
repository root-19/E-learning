<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the root path
define('ROOT_PATH', dirname(__DIR__));

// Include necessary files
require_once ROOT_PATH . '/app/config/database.php';
require_once ROOT_PATH . '/app/Router.php';

// Start session
session_start();

// Include routes
require_once ROOT_PATH . '/routes/web.php';

// Handle the request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router = new \root_dev\Router();
$router->handleRequest($uri, $method);

?>
