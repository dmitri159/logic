<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii2mod\cart\Cart;
?>
<h1>Carts</h1>
<p><?php if($post) { print_r($post); } ?></p>
<p><?php if($query) { print_r($query); } ?></p>
<?php ActiveForm::begin(['id' => 'cart', 'action' => ['product/cart']]); ?>
<div class="pull-left dropdown">
	<?= Html::dropDownList('country',null,
		['1' => 'Malaysia', '2' => 'Singapore', '3' => 'Brunei'],
		['class' => 'btn btn-primary']) 
	?>
</div>
<div class="pull-right">
	<?= Html::submitButton('Validate', ['class' => 'btn btn-primary','value' => '1', 'name' => 'validate']) ?>
</div>
<div class="pull-right">
	<?= Html::input('text','promocode','',
		['class' => 'form-control','placeholder' => 'promocode']) 
	?>
</div>

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
<?php ActiveForm::end(); ?>
<?php else: ?>
	<p>No Item</p>
<?php endif ?>
</div>
</div>
<?php print_r($cart_total); ?>