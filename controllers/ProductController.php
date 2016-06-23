<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Promocode;

class ProductController extends Controller
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

    public function actionIndex()
    {
        $query = Product::find();
        $products = $query->orderBy('id')
        ->all();
        $post = Yii::$app->request->post();
        if(isset($post['product'])) {
            $id = Yii::$app->request->post('product');
            $quantity = Yii::$app->request->post('quantity'.$id);
            $model = Product::findOne($id);
            $item = Yii::$app->cart->put($model,$quantity);
            $this->redirect('cart');
        }
        return $this->render('index', [
            'products' => $products
        ]);
    }

    public function actionCart()
    {   
        $post = Yii::$app->request->post();
        $model = Yii::$app->cart->getPositions();
        $count = Yii::$app->cart->getCount();
        $total = Yii::$app->cart->getCost();
        self::$cart_total['total_b'] = $total;
        $query = '';
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
        /*if ($model) {
            Yii::$app->cart->put($model, 1);
            return $this->render('cart',['carts' => $cart]);
        }
        throw new NotFoundHttpException();*/

        return $this->render('cart',['carts' => $model, 'post' => $post, 'query' => $query, 'cart_total' => self::$cart_total]);
    }

    public function actionSummary()
    {   
        Yii::$app->cart->removeAll();
        /*$model = Product::findOne($id);
        if ($model) {
            Yii::$app->cart->put($model, 1);
            return $this->render('cart',['carts' => $cart]);
        }
        throw new NotFoundHttpException();*/
        
        return $this->render('summary');
    }
}