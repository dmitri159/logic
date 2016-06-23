<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

class Product extends ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    public function getLabel()
    {
        return $this->p_name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPrice()
    {
        return $this->p_price;
    }
}