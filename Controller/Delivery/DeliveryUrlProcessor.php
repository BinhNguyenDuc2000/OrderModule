<?php

class DeliveryUrlProcessor implements UrlProcessorInterface
{
    public function processTaskAction($uri)
    {
        $deliveryController = new DeliveryController();
        $deliveryController->setDeliveryModel(new DeliveryModel());
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strcmp($uri[2], "delivery") == 0) {
            switch (strtoupper($requestMethod)) {
                case "GET":
                    $deliveryController->getDeliveryInfo($uri);
                    break;
                default:
                    $deliveryController->methodNotSupported();
                    $deliveryController->sendOutput(
                        json_encode(array('error' => $deliveryController->strErrorDesc)),
                        array('Content-Type: application/json', $deliveryController->strErrorHeader)
                    );
                    break;
            }
        }
    }
}
