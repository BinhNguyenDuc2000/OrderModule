<?php
require (__DIR__) . "/vendor/autoload.php";
require "inc/bootstrap.php";

// Setting exception handler
set_exception_handler("ErrorHandler::handleException");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Setting up header
header('Content-type: application/json; charset=UTF-8');

// Check url
if (!(isset($uri[2]))) {
    http_response_code(404);
    echo (json_encode(array("error" => "Invalid url")));
    exit();
}


switch ($uri[2]) {
    case "test": {
            // Testing
            $urlProcessor = new TestUrlProcessor();
            break;
        }
    case "order": {
            $urlProcessor = new OrderUrlProcessor();
            break;
        }
    case "user": {
            $urlProcessor = new UserUrlProcessor();
            break;
        }
    case "delivery": {
            $urlProcessor = new DeliveryUrlProcessor();
            break;
        }
    default:
        http_response_code(404);
        echo (json_encode(array("error" => "Invalid url")));
        exit();
}

$urlProcessor->processTaskAction($uri);
