<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Product;
use app\models\Promocode;

class ProductController extends Controller
{
    

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
        $query = Product::calculateCart($post);
        $model = Yii::$app->cart->getPositions();
        /*if ($model) {
            Yii::$app->cart->put($model, 1);
            return $this->render('cart',['carts' => $cart]);
        }
        throw new NotFoundHttpException();*/

        return $this->render('cart',['carts' => $model, 'post' => $post, 'query' => $query, 'cart_total' => Product::getCartTotal()]);
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