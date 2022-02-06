<?php

class UserModel extends Database implements UserModelInterface{
    public function getUserOrderHistory($limit, $user_id, $api_token_key) 
    {
        // Authorizing request

        // Get orders
        $orders = $this->select("SELECT * FROM OrderTable WHERE user_id = $user_id ORDER BY order_id  ASC LIMIT ?", ["i", $limit]);
        if (!$orders){
            return null;
        }

        // Getting product list for orders
        foreach($orders as &$order)
            $order['product_list'] = $this->select("SELECT product_id, quantity from ProductInOrder WHERE order_id = ".$order['order_id']."");

        return $orders;
    }
}