<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class OrderModel extends Database
{
    public function getOrders($limit)
    {
        return $this->select("SELECT * FROM OrderTable ORDER BY order_id ASC LIMIT ?", ["i", $limit]);
    }

    public function getOrder($order_id)
    {
        return $this->select("SELECT * FROM OrderTable WHERE order_id = $order_id");
    }
}
?>