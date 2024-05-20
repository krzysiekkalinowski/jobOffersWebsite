<?php
require '../helpers.php';
require basePath('Database.php');
require basePath('Router.php');

//Initialize the router
$router = new Router();

//Get paths to router
$routes = require basePath('routes.php');

//Get current URI and HTPP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

//Route the request
$router->route($uri, $method);
