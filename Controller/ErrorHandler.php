<?php

class ErrorHandler{

    /**
     * Return error as json file
     */ 
    public static function handleException(Throwable $exception){
        http_response_code(500);
        echo json_encode([
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "message" => $exception->getMessage(),
            "line" => $exception->getLine()
        ]);
    }
}