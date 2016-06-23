<?php
use yii\helpers\Html;
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
		<?php if($message['message'] && $message['error']): ?>
			<div class="alert alert-warning" role="alert"><?= $message['message'] ?></div>
		<?php elseif($message['message'] && !$message['error']): ?>
			<div class="alert alert-success" role="alert"><?= $message['message'] ?></div>
		<?php endif ?>
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
<div class="row">
<div class="col-md-4">
<?= Html::beginForm(['product/removeall', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::submitButton('Remove All', ['class' => 'btn btn-primary btn-block', 'name' => 'remove']) ?>
<?= Html::endForm(); ?>
</div>
<div class="col-md-4">
<?= Html::beginForm(['product/removeproduct', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::submitButton('Remove Products', ['class' => 'btn btn-primary btn-block', 'name' => 'remove']) ?>
<?= Html::endForm(); ?>
</div>
<div class="col-md-4">
<?= Html::beginForm(['product/removepromo', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::submitButton('Remove Promo', ['class' => 'btn btn-primary btn-block', 'name' => 'remove']) ?>
<?= Html::endForm(); ?>
</div>
</div>
