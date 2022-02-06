<?php
class BaseController
{
    public $strErrorDesc = "";
    public $strErrorHeader = "";
    public $arrQueryStringParams = "";

    public function __construct()
    {
        $this->strErrorDesc = "";
        $this->strErrorHeader = "";
    }

    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     * 
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return $uri;
    }

    /**
     * Get querystring params.
     * 
     * @return array
     */
    protected function getQueryStringParams()
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }

    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput($data, $httpHeaders = array())
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
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
