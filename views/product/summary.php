<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<h1>Carts</h1>

    <?= $carts?>

<?php echo \Yii::$app->cart->getCount(); ?>
