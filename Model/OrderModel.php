<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class OrderModel extends Database
{
    public function getOrders($limit, $api_token_key)
    {
        return $this->select("SELECT * FROM OrderTable
                            WHERE user_id in (SELECT user_id FROM UserTable WHERE api_token_key = \"$api_token_key\")
                            ORDER BY order_id ASC LIMIT ?", ["i", $limit]);
    }

    public function getOrder($order_id, $api_token_key)
    {

        return $this->select("SELECT * FROM OrderTable WHERE order_id = $order_id 
                            AND user_id in (SELECT user_id FROM UserTable WHERE api_token_key = \"$api_token_key\")");
    }
}
?>