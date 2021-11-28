<?php
class OrderController extends BaseController
{
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'POST') {
            try {
                $orderModel = new OrderModel();

                $intLimit = 10;
                if (isset($_POST['limit']) && $_POST['limit'] > 0) {
                    $intLimit = $_POST['limit'];
                }

                if (isset($_POST['api_token_key'])) {
                    $apiTokenKey = $_POST['api_token_key'];
                } else {
                    throw new InvalidArgumentException("Invalid Argument");
                }

                $arrOrders = $orderModel->getOrders($intLimit, $apiTokenKey);
                if ($arrOrders != NULL) {
                    $responseData = json_encode($arrOrders);
                } else {
                    throw new Exception("Can't find any order");
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            } catch (InvalidArgumentException $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
    public function order_idAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'POST') {
            try {
                $orderModel = new OrderModel();
                if (isset($_POST['order_id']) && isset($_POST['api_token_key'])) {
                    $orderId = $_POST['order_id'];
                    $apiTokenKey = $_POST['api_token_key'];
                } else {
                    throw new InvalidArgumentException("Invalid Argument");
                }
                $arrOrder = $orderModel->getOrder($orderId, $apiTokenKey);
                if ($arrOrder != NULL) {
                    $responseData = json_encode($arrOrder);
                } else {
                    throw new Exception("Can't find any order");
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . '. Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            } catch (InvalidArgumentException $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 404 Not Found';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}
