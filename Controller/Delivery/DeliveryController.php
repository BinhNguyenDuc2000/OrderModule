<?php

class DeliveryController extends BaseController implements DeliveryControllerInterface {
    private $deliveryModel;

    public function setDeliveryModel(DeliveryModelInterface $deliveryModel)
    {
        $this->deliveryModel = $deliveryModel;
    }

    public function getDeliveryInfo($uri)
    {
        $orderId = $uri[3];
        $arrOrder = $this->deliveryModel->getDeliveryInfo($orderId, "");
        if ($arrOrder != NULL) {
            $responseData = json_encode($arrOrder);
        } else {
            $this->orderNotFound();
        }

        // send output
        if (!$this->strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $this->strErrorDesc)),
                array('Content-Type: application/json', $this->strErrorHeader)
            );
        }
    }
}