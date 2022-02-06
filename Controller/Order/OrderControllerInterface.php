<?php
interface OrderControllerInterface
{
    public function setOrderModel(OrderModelInterface $orderModel);
    public function setDeliveryModule(DeliveryModuleInterface $deliveryModulde);
    public function setPromotionModule(PromotionModuleInterface $promotionModule);
    
    public function indexAction();
    public function createAction();
    public function getAction($uri);
    public function updateStatusAction($uri);
}