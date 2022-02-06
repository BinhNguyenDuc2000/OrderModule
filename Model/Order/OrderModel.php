<?php

class OrderModel extends Database implements OrderModelInterface
{
    public function getOrders($limit, $api_token_key)
    {
        // Authorizing request

        // Get orders
        $orders = $this->select("SELECT * FROM OrderTable
                            ORDER BY order_id ASC LIMIT ?", ["i", $limit]);
        if (!$orders) {
            return null;
        }

        // Getting product list for orders
        foreach ($orders as &$order)
            $order['product_list'] = $this->select("SELECT product_id, quantity from ProductInOrder WHERE order_id = " . $order['order_id'] . "");

        return $orders;
    }

    /**
     * Getting order from the database after Authorizing request
     */
    public function getOrder($order_id, $api_token_key)
    {
        // Authorizing request

        // Get order
        $order = $this->select("SELECT * FROM OrderTable WHERE order_id = $order_id");
        if (!$order) {
            return null;
        }

        // Getting product list for order
        $order[0]['product_list'] = $this->select("SELECT product_id, quantity from ProductInOrder WHERE order_id = $order_id");

        // Returning order
        return $order[0];
    }

    public function createOrder($order, $api_token_key)
    {
        // Authorizing request

        // Get order
        $user_id = $order['user_id'];
        $delivery_note = !empty($order['delivery_note']) ? $order['delivery_note'] : "NULL";
        $to_address = $order["to_address"];
        $from_address = $order['from_address'];
        $name = $order['name'];
        $phone_number = $order['phone_number'];
        $shipping_unit = $order['shipping_unit'];
        $shipping_voucher = $order['shipping_voucher'];
        $shipping_fee = $order['shipping_fee'];
        $total = $order['total'];
        $subtotal = $order['subtotal'];
        $payment_method = $order['payment_method'];
        $query = "INSERT INTO OrderTable" .
            "(user_id, total, subtotal, delivery_note, to_address, from_address, name, phone_number, shipping_unit, shipping_voucher, shipping_fee, payment_method, status, order_timestamp)" .
            "VALUES ($user_id, $total, $subtotal, '$delivery_note', '$to_address', '$from_address', '$name', '$phone_number', '$shipping_unit', '$shipping_voucher', $shipping_fee, '$payment_method', 'unpaid', CURRENT_TIMESTAMP())";

        if (!$this->connection->query($query)) {
            throw new Exception("Can't insert order");
        }

        // Get inserted order ID         
        $order_id = $this->connection->insert_id;

        // Returning order if there are no product list
        if (!isset($order['product_list']))
            return $this->getOrder($order_id, $api_token_key);

        foreach ($order['product_list'] as $product) {
            if (!$this->executeStatement("INSERT INTO ProductInOrder (order_id, product_id, quantity)
                                        VALUES ($order_id, " . $product['product_id'] . ", " . $product['quantity'] . ")")) {
                throw new Exception("Can't insert product list");
            }
        }
        return $this->getOrder($order_id, $api_token_key);
    }

    public function updateOrderStatus($order_id, $status, $api_token_key)
    {
        // Authorizing request

        // Run update
        switch ($status) {
            case "cancelled": {
                    return $this->executeStatement("UPDATE OrderTable
                                        SET status = \"$status\", cancel_timestamp = CURRENT_TIMESTAMP()
                                        WHERE order_id = $order_id");
                    break;
                }

            case "delivered": {
                    return $this->executeStatement("UPDATE OrderTable
                                        SET status = \"$status\", deliver_timestamp = CURRENT_TIMESTAMP()
                                        WHERE order_id = $order_id");
                    break;
                }
            default:
                return $this->executeStatement("UPDATE OrderTable
                                        SET status = \"$status\"
                                        WHERE order_id = $order_id");
        }
    }
}
