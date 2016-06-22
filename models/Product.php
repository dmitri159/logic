<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii2mod\cart\models\CartItemInterface;

class Product extends ActiveRecord implements CartItemInterface
{

    public function getPrice()
    {
        return $this->price;
    }

    public function getLabel()
    {
        return $this->name;
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