<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii2mod\cart\models\CartItemInterface;

class Product extends ActiveRecord implements CartItemInterface
{

    public function getPrice()
    {
        return $this->p_discount;
    }

    public function getLabel()
    {
        return $this->p_name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUniqueId()
    {
        return $this->id;
    }
}