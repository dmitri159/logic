<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<h1>Summary</h1>
<div class="row">
<div class="col-md-12">
<?php if($carts): ?>
<?php foreach($carts as $cart): ?> 
	<?php foreach($cart as $title): ?>
		<?= $title ?>
	<?php endforeach ?>
    	: [<?= $cart['_quantity'] ?>]
<?php endforeach?>
<?php Html::hiddenInput('price', \Yii::$app->cart->getCost()); ?>

<?php else: ?>
	<p>No Item</p>
<?php endif ?>
</div>
</div>
<?php print_r($cart_total); ?>
