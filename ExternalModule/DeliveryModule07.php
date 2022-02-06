<?php

class DeliveryModule07 extends Api implements DeliveryModuleInterface
{
    public function getShippingFee($fromAddress, $toAddress)
    {
        try {
            if (!isset($fromAddress) || !isset($toAddress)){
                return 0;
            }
            $data = json_decode($this->get(sprintf(
                "https://ltct-sp-07.herokuapp.com/api/getShippingFee?from_address%s=&to_address=%s",
                $fromAddress,
                $toAddress
            )));

            $shippingFee = $data->{'fee'};
            return $shippingFee;
        } catch (Exception $e) {
            return 0;
        }
    }
}
