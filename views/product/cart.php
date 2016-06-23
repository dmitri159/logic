<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii2mod\cart\Cart;
use yii\helpers\ArrayHelper;
use app\models\Country;
?>
<h1>Carts</h1>
<?= Html::beginForm(['product/cart', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
<p><?php if($post) { print_r($post); } ?></p>
<div class="row">
	<div class="col-lg-4">
		<div class="input-group dropdown">
			<?= Html::dropDownList('country',$selected['country'],ArrayHelper::map(Country::find()->all(),'code','name'),
				['class' => 'btn btn-default dropdown-toggle', 'required' => 'required']) 
			?>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="input-group">
		  <?= Html::input('text','promocode',$selected['promo'],['class' => 'form-control','placeholder' => 'promocode']) ?>
		  <span class="input-group-btn">
		    <?= Html::submitInput('Validate and Calculate Shipping', ['class' => 'btn btn-primary', 'name' => 'validate']) ?>
		  </span>
		</div><!-- /input-group -->
	</div>
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

<?php else: ?>
	<p>No Item</p>
<?php endif ?>
</div>
</div>
<div class="row">
<div class="col-lg-12 pull-left">
<?= Html::submitInput('Checkout', ['class' => 'btn btn-primary', 'name' => 'checkout']) ?>
</div>
</div>
<?= Html::endForm(); ?>
<?php print_r($cart_total); ?>