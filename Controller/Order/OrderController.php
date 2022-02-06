<?php

class OrderController extends BaseController implements OrderControllerInterface
{
    private $orderModel;
    private $deliveryModule;
    private $promotionModule;

    public function setOrderModel(OrderModelInterface $orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function setDeliveryModule(DeliveryModuleInterface $deliveryModulde)
    {
        $this->deliveryModule = $deliveryModulde;
    }

    public function setPromotionModule(PromotionModuleInterface $promotionModulde)
    {
        $this->promotionModule = $promotionModulde;
    }

    /**
     * Print list of orders
     */
    public function indexAction()
    {

        $arrQueryStringParams = $this->getQueryStringParams();
        $intLimit = 10;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit'] > 0) {
            $intLimit = $arrQueryStringParams['limit'];
        }

        $arrOrders = $this->orderModel->getOrders($intLimit, "");

        if ($arrOrders != NULL) {
            $responseData = json_encode($arrOrders);
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

    public function createAction()
    {

        $order = json_decode(file_get_contents("php://input"), true);
        try {
            $order['shipping_fee'] = $this->deliveryModule->getShippingFee($order['from_address'], $order['to_address']);
            $this->promotionModule->setPromotion($order['shipping_voucher']);
            if ($this->promotionModule->checkCondition($order['total'], count($order['product_list']))) {
                $order['total'] = $this->promotionModule->getSubtotal($order['subtotal'] + $order['shipping_fee']);
            } else {
                $order['total'] = $order['subtotal'] + $order['shipping_fee'];
            }
            $responseData = json_encode($this->orderModel->createOrder($order, ""));
        } catch (Exception $e) {
            $this->failedToCreateOrder();
        }


        // send output
        if (!$this->strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 201 Created')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $this->strErrorDesc)),
                array('Content-Type: application/json', $this->strErrorHeader)
            );
        }
    }

    public function getAction($uri)
    {
        $orderId = (int)$uri[3];
        $arrOrder = $this->orderModel->getOrder($orderId, "");
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

    public function updateStatusAction($uri)
    {
        $order = json_decode(file_get_contents("php://input"), true);
        $orderId = (int)$uri[3];
        $status = $order["status"];
        if ($status != "unpaid" && $status != "active" && $status != "cancelled" && $status != "delivered") {
            $this->invalidArgument();
        } else { //Set order model before query
            $arrOrder = $this->orderModel->updateOrderStatus($orderId, $status, "");
            if ($arrOrder) {
                $responseData = json_encode(array("Message" => "Order updated successfully"));
            } else {
                $this->failedToUpdateOrder();
            }
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
