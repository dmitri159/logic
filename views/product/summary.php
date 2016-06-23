<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii2mod\cart\Cart;
?>
<h1>Summary</h1>


<?php echo \Yii::$app->cart->getCount(Cart::ITEM_PRODUCT); ?>
