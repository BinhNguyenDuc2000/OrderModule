<?php

class OrderUrlProcessor implements UrlProcessorInterface
{   
    public function processTaskAction($uri)
    {
        $orderController = new OrderController();
        $orderController->setOrderModel(new OrderModel());
        $orderController->setDeliveryModule(new DeliveryModule07());
        $orderController->setPromotionModule(new PromotionModule19());
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
                    break;
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
                    break;
            }
        }
    }
}
