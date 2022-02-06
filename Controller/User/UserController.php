<?php

class UserController extends BaseController implements UserControllerInterface {
    private $userModel;

    public function setUserModel(UserModelInterface $userModel){
        $this->userModel = $userModel;
    }


    public function getUserOrderHistory($uri)
    {
        if (isset($uri[3])) {
            $userId = (int)$uri[3];
            $arrQueryStringParams = $this->getQueryStringParams();
            $intLimit = 10;
            if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit'] > 0) {
                $intLimit = $arrQueryStringParams['limit'];
            }

            $arrOrders = $this->userModel->getUserOrderHistory($intLimit, $userId, "");
        } else {
            $this->invalidArgument();
        }

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
}