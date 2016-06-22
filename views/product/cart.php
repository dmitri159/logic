<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<h1>Carts</h1>
<?php foreach($carts as $cart): ?> 
    <?= $cart?>

<?php endforeach?>

<?php echo \Yii::$app->cart->getAttributeTotal('p_discount'); ?>
