<?php

interface DeliveryModuleInterface
{
    public function getShippingFee($fromAddress, $toAddress);
}