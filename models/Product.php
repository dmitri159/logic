<?php

namespace app\models;

use yii;
use yii\db\ActiveRecord;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

class Product extends ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    public function getId()
    {
        return $this->id;
    }

    public function getPrice()
    {
        return $this->p_discount;
    }

    public function getRetails() {
        return $this->p_price;
    }
}