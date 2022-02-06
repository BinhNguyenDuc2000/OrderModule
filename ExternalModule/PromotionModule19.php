<?php

class PromotionModule19 extends Api implements PromotionModuleInterface
{
    public function setPromotion($code)
    {
        try {
            $response = json_decode($this->get(
                sprintf("https://ltct-sp19-api.herokuapp.com/api/sale/code/admin/%s", $code)
            ))->{'data'};
            $this->discount = $response->{'discount'};
            $this->condition = $response->{'condition'};
            $this->deleted = $response->{'deleted'} ? true : false;
            $this->isActived = $response->{'isActived'} ? true : false;
        } catch (Exception $e) {
        }
    }

    public function getSubtotal($total)
    {
        try {
            switch ($this->discount->{'discountType'}) {
                case 1:
                    $reduct = min($total * $this->discount->{'discountValue'} / 100, $this->discount->{'subConditions'});

                    return $total - $reduct;
                    break;
                case 2:
                    $reduct = min($this->discount->{'discountValue'}, $this->discount->{'subConditions'});
                    return $total - $reduct;
                    break;
                default:
                    return $total;
            }
        } catch (Exception $e) {
            return $total;
        }
    }
    public function checkCondition($price, $totalProduct)
    {
        try {
            if ($this->deleted || !$this->isActived) {
                return false;
            }
            switch ($this->condition->{'conditionType'}) {
                case 1:
                    return ($price >= $this->condition->{'conditionValue'});
                case 2:
                    return ($totalProduct >= $this->condition->{'conditionValue'});
                default:
                    return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
