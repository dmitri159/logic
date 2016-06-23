<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii2mod\cart\Cart;
?>
<h1>Carts</h1>
<?php $form = ActiveForm::begin(['id' => 'cart', 'action' => ['product/cart']]); ?>
<div class="dropdown">
	    	<?= Html::dropDownList('country',null,
	    		['1' => 'Malaysia', '2' => 'Singapore', '3' => 'Brunei'],
	    		['class' => 'btn btn-primary']) 
	    	?>
		</div>
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

<?php echo \Yii::$app->cart->getCost(); ?>
