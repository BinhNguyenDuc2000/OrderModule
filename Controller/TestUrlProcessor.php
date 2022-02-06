<?php

class TestUrlProcessor implements UrlProcessorInterface
{
    public function processTaskAction($uri)
    {
        if (isset($uri[3]) && strcmp($uri[3], "shipping") == 0) {
            $deliverModule = new DeliveryModule07();
            echo json_encode(array("price" => $deliverModule->getShippingFee("Hoàn Kiếm - Hà Nội", "Thanh Xuân - Hà Nội")));
        }
        if (isset($uri[3]) && strcmp($uri[3], "promotion") == 0) {
            $promotionModule = new PromotionModule19();
            $price = 156000;
            $totalProduct = 10;
            $promotionModule->setPromotion("61eb8578aecf1f315ec9a8c8");
            if ($promotionModule->checkCondition($price, $totalProduct)) {
                $subtotal = $promotionModule->getSubtotal($price);
            } else {
                $subtotal = $price;
            }
            echo json_encode(array("price" => $subtotal));
        }
    }
}
