<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii2mod\cart\Cart;
?>
<h1>Carts</h1>
<?php foreach($carts as $cart): ?> 
    <?php print_r($cart)?>

<?php endforeach?>

<?php echo \Yii::$app->cart->getCost(); ?>
