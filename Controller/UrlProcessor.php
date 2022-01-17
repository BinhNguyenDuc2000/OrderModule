<?php

class UrlProcessor implements UrlProcessInterface
{
    public function processTaskAction($uri, OrderControllerInterface $controller)
    {
        if ($uri[2] == "order") {
            $orderController = $controller;
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            if (!isset($uri[3]) || strcmp($uri[3], "") == 0) {
                switch (strtoupper($requestMethod)) {
                    case "GET":
                        $orderController->indexAction();
                        break;
                    case "PATCH":
                        break;
                    case "POST":
                        $orderController->createAction();
                        break;
                    default:
                        $orderController->methodNotSupported();
                        $orderController->sendOutput(
                            json_encode(array('error' => $orderController->strErrorDesc)),
                            array('Content-Type: application/json', $orderController->strErrorHeader)
                        );
                }
            } else {
                switch (strtoupper($requestMethod)) {
                    case "GET":
                        $orderController->getAction($uri);
                        break;
                    case "PATCH":
                        $orderController->updateStatusAction($uri);
                        break;
                    default:
                        $orderController->methodNotSupported();
                        $orderController->sendOutput(
                            json_encode(array('error' => $orderController->strErrorDesc)),
                            array('Content-Type: application/json', $orderController->strErrorHeader)
                        );
                }
            }
        }
    }
}