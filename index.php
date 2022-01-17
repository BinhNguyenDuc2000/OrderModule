<?php
require (__DIR__) . "/vendor/autoload.php";
require "inc/bootstrap.php";

set_exception_handler("ErrorHandler::handleException");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// Check url
if (!(isset($uri[2]))) {
    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    // header("Content-Type: application/json");
    http_response_code(404);
    echo(json_encode(array("error"=>"Invalid url")));
    exit();
}

header('Content-type: application/json; charset=UTF-8');

$urlProcessor = new UrlProcessor();
$orderModel = new OrderModel();
$urlProcessor->processTaskAction($uri, new OrderController($orderModel));
