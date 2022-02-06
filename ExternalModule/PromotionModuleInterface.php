<?php

interface PromotionModuleInterface 
{
    public function setPromotion($code);
    public function getSubtotal($total);
    public function checkCondition($price, $totalProduct);
}