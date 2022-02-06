<?php

interface DeliveryControllerInterface {
    public function setDeliveryModel(DeliveryModelInterface $deliveryModel);

    public function getDeliveryInfo($uri);
}