<?php
require (__DIR__) . "/vendor/autoload.php";
require "inc/bootstrap.php";

set_exception_handler("ErrorHandler::handleException");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// Check url
if ((isset($uri[2]) && $uri[2] != 'order')) {
    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    // header("Content-Type: application/json");
    http_response_code(404);
    echo(json_encode(array("error"=>"Invalid url")));
    exit();
}

header('Content-type: application/json; charset=UTF-8');
 
$objFeedController = new OrderController();
if (!isset($uri[3]) || strcmp($uri[3],"")==0){
    $strMethodName = "processTaskAction";
}
else{
    $strMethodName = "getAction";
}
if (method_exists($objFeedController, $strMethodName))
    $objFeedController->{$strMethodName}();
else{
    throw new Exception("Method not found");
}
?>