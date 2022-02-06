<?php

class DeliveryModel extends Database implements DeliveryModelInterface {
    public function getDeliveryInfo($order_id, $api_token_key) {
        // Authorizing request

        // Get order
        $order = $this->select("SELECT total, subtotal, delivery_note, from_address, to_address, name, phone_number, shipping_unit, shipping_voucher, shipping_fee, payment_method, status FROM OrderTable WHERE order_id = $order_id");
        if (!$order) {
            return null;
        }
        
        // Returning order
        return $order[0];
    }
}