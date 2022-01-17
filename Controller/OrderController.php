<?php

class OrderController extends BaseController implements OrderControllerInterface
{
    public $orderModel;
    public function __construct(OrderModelInterface $orderModel)
    {
        BaseController::__construct();
        $this->orderModel = $orderModel;
    }

    /**
     * Print list of orders
     */
    public function indexAction()
    {

        $arrQueryStringParams = $this->getQueryStringParams();

        $this->orderModel = new OrderModel();
        $intLimit = 10;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit'] > 0) {
            $intLimit = $arrQueryStringParams['limit'];
        }

        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            $apiTokenKey = $_SERVER['HTTP_X_API_KEY'];
            $arrOrders = $this->orderModel->getOrders($intLimit, $apiTokenKey);

            if ($arrOrders != NULL) {
                $responseData = json_encode($arrOrders);
            } else {
                $this->orderNotFound();
            }
        } else {
            $this->invalidArgument();
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
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            $order = json_decode(file_get_contents("php://input"), true);
            $apiTokenKey = $_SERVER['HTTP_X_API_KEY'];
            //Set order model before query
            $this->orderModel = new OrderModel();
            $arrOrder = $this->orderModel->createOrder($order, $apiTokenKey);
            if ($arrOrder) {
                $responseData = json_encode(array("Message" => "Order created successfully"));
            } else {
                $this->failedToCreateOrder();
            }
        } else {
            $this->invalidArgument();
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
        if (isset($uri[3]) && isset($_SERVER['HTTP_X_API_KEY'])) {
            $orderId = (int)$uri[3];
            $apiTokenKey = $_SERVER['HTTP_X_API_KEY'];
            //Set order model before query
            $this->orderModel = new OrderModel();
            $arrOrder = $this->orderModel->getOrder($orderId, $apiTokenKey);
            if ($arrOrder != NULL) {
                $responseData = json_encode($arrOrder);
            } else {
                $this->orderNotFound();
            }
        } else {
            $this->invalidArgument();
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

    public function updateStatusAction($uri){
        $order = json_decode(file_get_contents("php://input"), true);
        if (isset($uri[3]) && isset($_SERVER['HTTP_X_API_KEY']) && isset($order["status"])) {
            $orderId = (int)$uri[3];
            $apiTokenKey = $_SERVER['HTTP_X_API_KEY'];
            $status = $order["status"];
            //Set order model before query
            $this->orderModel = new OrderModel();
            $arrOrder = $this->orderModel->updateOrderStatus($orderId, $status, $apiTokenKey);
            if ($arrOrder) {
                $responseData = json_encode(array("Message" => "Order updated successfully"));
            } else {
                $this->failedToUpdateOrder();
            }
        } else {
            $this->invalidArgument();
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

    // Error messages
    /**
     * When the order is not found within the database.
     */
    public function orderNotFound()
    {
        $this->strErrorDesc = "Order not found";
        $this->strErrorHeader = 'HTTP/1.1 404 Not Found';
    }

    /**
     * When the api argument is not correct.
     */
    public function invalidArgument()
    {
        $this->strErrorDesc = "Invalid Argument";
        $this->strErrorHeader = 'HTTP/1.1 400 Bad Request';
    }

    /**
     * When the method use is not correct.
     */
    public function methodNotSupported()
    {
        $this->strErrorDesc = 'Method not supported';
        $this->strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }

    /**
     * When failed to create new order
     */
    public function failedToCreateOrder()
    {
        $this->strErrorDesc = 'Failed to create new order';
        $this->strErrorHeader = 'HTTP/1.1 500';
    }

    /**
     * When failed to update new order
     */
    public function failedToUpdateOrder()
    {
        $this->strErrorDesc = 'Failed to update order';
        $this->strErrorHeader = 'HTTP/1.1 500';
    }
}
