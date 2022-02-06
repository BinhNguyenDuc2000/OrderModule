<?php
interface OrderModelInterface
{
    public function getOrders($limit, $api_token_key);
    public function getOrder($order_id, $api_token_key);
    public function createOrder($order, $api_token_key);
    public function updateOrderStatus($order_id, $status, $api_token_key);
}