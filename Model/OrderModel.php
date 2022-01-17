<?php
require_once PROJECT_ROOT_PATH . "/Model/AuthModel.php";
 
class OrderModel extends Database implements OrderModelInterface
{
    public function getOrders($limit, $api_token_key) 
    {
        // Authorizing request

        // Get orders
        $orders = $this->select("SELECT * FROM OrderTable
                            ORDER BY order_id ASC LIMIT ?", ["i", $limit]);
        if (!$orders){
            return null;
        }

        // Getting product list for orders
        foreach($orders as &$order)
            $order['product_list'] = $this->select("SELECT product_id, quantity from ProductInOrder WHERE order_id = ".$order['order_id']."");

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
        if (!$order){
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
        $user_id = 2;
        $note = !empty($order['note']) ? "'".$order['note']."'" : "NULL";
        $insert_order = $this->executeStatement("INSERT INTO OrderTable (user_id, delivery_note, status) VALUES ($user_id, $note, 'active')");
        // Returning order if there are no product list
        if (!isset($order['product_list']) )
            return $insert_order;
        
        // Get inserted order ID
        $order_id = $this->select("SELECT LAST_INSERT_ID()");
        if (!$order_id[0])
            throw new Exception("Failed to fetch last insert ID");           
        $order_id = $order_id[0]['LAST_INSERT_ID()'];

        $is_complete = true;
        foreach($order['product_list'] as $product){
            if (!$this->executeStatement("INSERT INTO ProductInOrder (order_id, product_id, quantity)
                                        VALUES ($order_id, ".$product['product_id'].", ".$product['quantity'].")")){
                $is_complete = false;
            }
        }
        return $is_complete;
    }

    public function updateOrderStatus($order_id, $status, $api_token_key){
        // Authorizing request

        // Run update
        return $this->executeStatement("UPDATE OrderTable
                                        SET status = \"$status\"
                                        WHERE order_id = $order_id");
    }
}
?>