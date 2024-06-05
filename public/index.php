<?php
require __DIR__ . '/../vendor/autoload.php';


use Framework\Router;
use Framework\Session;

Session::start();

require '../helpers.php';

//Initialize the router
$router = new Router();

//Get paths to router
$routes = require basePath('routes.php');

//Get current URI and HTPP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


//Route the request
$router->route($uri);
