<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Promocode;

class ProductController extends Controller
{
    protected static $cartItem = array();

    public function __contruct() {
        self::$product = new Product();
    }

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
        if(isset($post['checkout'])) $this->redirect('summary');
        $selected = array(
            'country' => null,
            'promo' => null
        );
        if(isset($post['country'])) $selected['country'] = $post['country'];
        if(isset($post['promocode'])) $selected['promo'] = $post['promocode'];
        Yii::$app->cart->calculateCart($post);
        $model = Yii::$app->cart->getPositions();
        $cartItem = Yii::$app->cart->getCartTotal();
        return $this->render('cart',['carts' => $model, 'post' => $post, 'cart_total' => $cartItem, 'selected' => $selected]);
        
    }

    public function actionSummary()  {
        $cartItem = Yii::$app->cart->getCartTotal();
        return $this->render('summary',['cart_total' => $cartItem]);
    }
}