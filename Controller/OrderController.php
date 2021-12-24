<?php
class OrderController extends BaseController
{
    public function __construct()
    {
        BaseController::__construct();
    }

    public function processTaskAction()
    {
        $this->strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        switch (strtoupper($requestMethod)) {
            case "GET":
                $this->indexAction();
                break;
            case "POST":
                $this->createAction();
                break;
            default:
                methodNotSupported();
                $this->sendOutput(
                    json_encode(array('error' => $this->strErrorDesc)),
                    array('Content-Type: application/json', $this->strErrorHeader)
                );
        }
    }

    /**
     * Print list of orders
     */
    public function indexAction()
    {

        $arrQueryStringParams = $this->getQueryStringParams();

        $orderModel = new OrderModel();
        $intLimit = 10;
        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit'] > 0) {
            $intLimit = $arrQueryStringParams['limit'];
        }

        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            $apiTokenKey = $_SERVER['HTTP_X_API_KEY'];
            $arrOrders = $orderModel->getOrders($intLimit, $apiTokenKey);

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

    public function getAction()
    {
        $this->strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'GET') {
            $uri = $this->getUriSegments();
            if (isset($uri[3]) && isset($_SERVER['HTTP_X_API_KEY'])) {
                $orderId = (int)$uri[3];
                $apiTokenKey = $_SERVER['HTTP_X_API_KEY'];
                //Set order model before query
                $orderModel = new OrderModel();
                $arrOrder = $orderModel->getOrder($orderId, $apiTokenKey);
                if ($arrOrder != NULL) {
                    $responseData = json_encode($arrOrder);
                } else {
                    $this->orderNotFound();
                }
            } else {
                $this->invalidArgument();
            }
        } else {
            $this->methodNotSupported();
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
        $uri = $this->getUriSegments();
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            $order = json_decode(file_get_contents("php://input"), true);
            $apiTokenKey = $_SERVER['HTTP_X_API_KEY'];
            //Set order model before query
            $orderModel = new OrderModel();
            $arrOrder = $orderModel->createOrder($order, $apiTokenKey);
            if ($arrOrder) {
                $responseData = json_encode(array("Message" => "Order created successfully"));
            }
            else {
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

    /**
     * When the order is not found within the database.
     */
    private function orderNotFound()
    {
        $this->strErrorDesc = "Order not found";
        $this->strErrorHeader = 'HTTP/1.1 404 Not Found';
    }

    /**
     * When the api argument is not correct.
     */
    private function invalidArgument()
    {
        $this->strErrorDesc = "Invalid Argument";
        $this->strErrorHeader = 'HTTP/1.1 400 Bad Request';
    }

    /**
     * When the method use is not correct.
     */
    private function methodNotSupported()
    {
        $this->strErrorDesc = 'Method not supported';
        $this->strErrorHeader = 'HTTP/1.1 405 Method Not Allowed';
    }

    /**
     * When failed to create new order
     */
    private function failedToCreateOrder()
    {
        $this->strErrorDesc = 'Failed to create new order';
        $this->strErrorHeader = 'HTTP/1.1 500';
    }
}
