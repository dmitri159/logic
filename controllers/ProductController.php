<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Promocode;
use yz\shoppingcart\ShoppingCart;

class ProductController extends Controller
{
    protected $cart;
    protected $final;
    public function init() {
        $this->cart = new ShoppingCart();
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
            $this->cart->put($model,$quantity);
            $this->cart->calculate();
            $this->redirect('cart');
        }
        $cartItem = $this->cart->getCartTotal();
        return $this->render('index', [
            'products' => $products,
            'cart_total' => $cartItem
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
        $arr = $this->cart->calculateCart($post);
        if(isset($arr['country'])) $selected['country'] = $arr['country'];
        if(isset($arr['promocode'])) $selected['promo'] = $arr['promocode'];
        $model = $this->cart->getPositions();
        $cartItem = $this->cart->getCartTotal();
        return $this->render('cart',['carts' => $model, 'post' => $post, 'cart_total' => $cartItem, 'selected' => $selected,'message' => $arr]);
        
    }

    public function actionSummary()  {
        $cartItem = $this->cart->getCartTotal();
        $model = $this->cart->getPositions();
        return $this->render('summary',['carts' => $model, 'cart_total' => $cartItem]);
    }

    public function actionRemoveall() {
        $this->cart->removeAll();
        $this->redirect('index');
    }

    public function actionRemoveproduct() {
        $this->cart->removeProduct();
        $this->redirect('index');
    }

    public function actionRemovepromo() {
        $this->cart->removePromo();
        $this->redirect('cart');
    }
}