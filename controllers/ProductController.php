<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\Product;

class ProductController extends Controller
{

    public function actionIndex()
    {
        $query = Product::find();
        $products = $query->orderBy('id')
        ->all();
        $listData = array('1' => '1');
        foreach(range(2,10) as $number) {
            array_push($listData, $number);
        }
        $post = Yii::$app->request->post();
        if(isset($post['product'])) {
            $id = Yii::$app->request->post('product');
            $quantity = Yii::$app->request->post('quantity');
            $model = Product::findOne($id);
            $item = Yii::$app->cart->add($model,$quantity);
            print_r($item);
            //$this->redirect('cart');
        }
        return $this->render('index', [
            'products' => $products
        ]);
    }

    public function actionCart()
    {   
        $model = Yii::$app->cart->getItems();
        /*if ($model) {
            Yii::$app->cart->put($model, 1);
            return $this->render('cart',['carts' => $cart]);
        }
        throw new NotFoundHttpException();*/

        return $this->render('cart',['carts' => $model]);
    }

    public function actionSummary()
    {   
        /*$model = Product::findOne($id);
        if ($model) {
            Yii::$app->cart->put($model, 1);
            return $this->render('cart',['carts' => $cart]);
        }
        throw new NotFoundHttpException();*/
        
        return $this->render('summary');
    }
}