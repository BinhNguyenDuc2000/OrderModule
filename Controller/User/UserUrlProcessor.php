<?php

class UserUrlProcessor implements UrlProcessorInterface{
    public function processTaskAction($uri)
    {
        $userController = new UserController();
        $userController->setUserModel(new UserModel());
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strcmp($uri[2], "user") == 0) {
            switch (strtoupper($requestMethod)) {
                case "GET":
                    $userController->getUserOrderHistory($uri);
                    break;
                default:
                    $userController->methodNotSupported();
                    $userController->sendOutput(
                        json_encode(array('error' => $userController->strErrorDesc)),
                        array('Content-Type: application/json', $userController->strErrorHeader)
                    );
                    break;
            }
        }
    }
}