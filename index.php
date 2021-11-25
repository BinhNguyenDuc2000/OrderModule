<?php
require "inc/bootstrap.php";
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );


if ((isset($uri[3]) && $uri[3] != 'order') || !isset($uri[4])) {
    header("HTTP/1.1 404 Not Found");
    header("Content-Type: application/json");
    echo(json_encode(array("error"=>"Invalid url")));
    exit();
}
 
require PROJECT_ROOT_PATH . "/Controller/Api/OrderController.php";
 
$objFeedController = new OrderController();
$strMethodName = $uri[4] . 'Action';
$objFeedController->{$strMethodName}();

?>