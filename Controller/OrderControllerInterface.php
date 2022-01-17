<?php
interface OrderControllerInterface
{
    public function indexAction();
    public function createAction();
    public function getAction($uri);
    public function updateStatusAction($uri);
    public function orderNotFound();
    public function invalidArgument();
    public function methodNotSupported();
    public function failedToCreateOrder();
    public function failedToUpdateOrder();
}