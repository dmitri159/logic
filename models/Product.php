<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

class Product extends ActiveRecord implements CartPositionInterface
{
    protected static $MALAYSIA = 10;
    protected static $SINGAPORE = 20;
    protected static $BRUNEI = 25;

    protected static $cart_total = array (
        'total' => 0,
        'total_b' => 0,
        'shipping' => 0,
        'discount' => 0
    );

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

    public function calculateCart($post) {
        
        $count = Yii::$app->cart->getCount();
        $total = Yii::$app->cart->getCost();
        self::$cart_total['total_b'] = $total;
        if($post) {
            if($post['validate']) {
                $promocode = strtoupper($post['promocode']);
                $country = $post['country'];
                $query = Promocode::find()->where(['p_code' =>  $promocode])->one();
                if(!$query) {
                    $query = 'Empty';
                }
                else {
                    $discount = $query['p_discount'];
                    if($query['p_type'] == 'F') {
                        if($count > $query['p_quantity'] || $total > $query['p_total']) {
                            $query = 'Success '.$query['p_code'];
                            self::$cart_total['discount'] = $discount;
                            self::$cart_total['total_b'] = $total - $discount;
                        }
                    }
                    else if($query['p_type'] == 'P') {
                        if($count > $query['p_quantity'] || $total > $query['p_total']) {
                            $query = 'Success '.$query['p_code'];
                            self::$cart_total['discount'] = $discount;
                            self::$cart_total['total_b'] = ($total * ( 100 - $discount)) / 100;
                        }
                    }
                }
                if($post['country'] == 1) {
                    if($count < 2 || $total < 150) {
                        self::$cart_total['shipping'] = self::$MALAYSIA;
                    }
                }
                else if($post['country'] == 2) {
                    if($total < 300) {
                        self::$cart_total['shipping'] = self::$SINGAPORE;
                    }
                } 
                else if($post['country'] == 3) {
                    if($total < 300) {
                        self::$cart_total['shipping'] = self::$BRUNEI;
                    }
                }
            }
        }
        self::$cart_total['total'] = self::$cart_total['total_b'] + self::$cart_total['shipping'];
    }

    public function getCartTotal() {
        return self::$cart_total;
    }

}