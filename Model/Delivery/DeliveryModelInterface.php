<?php

interface DeliveryModelInterface {
    public function getDeliveryInfo($order_id, $api_token_key);
}