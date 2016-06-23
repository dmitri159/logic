<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Country;
?>
<h1>Carts</h1>
<div class="row">
	<div class="col-md-12">
		<?php if($carts): ?>
			<table class="table">
				<thead>
					<tr>
						<th>Item</th><th>Name</th><th>Price</th><th>Quantity</th><th>Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($carts as $cart): ?> 
						<?php	$img = Url::to('@web/upload'.$cart['p_img']); ?>
						<tr>
						<th><?= Html::img($img,['class' => 'img', 'width' => '300px !important', 'height' => '200px !important']) ?></th><td><?= $cart['p_name'] ?></td><td><strike>MYR<?= $cart['p_price'] ?></strike><br/><strong>MYR<?= $cart['p_discount'] ?></strong></td><td><?= $cart['_quantity'] ?></td><td><?= $cart['p_discount'] * $cart['_quantity'] ?></td>
						</tr>
					<?php endforeach?>
				</tbody>
			</table>
		<?php else: ?>
			<p>No Item</p>
		<?php endif ?>
	</div>
</div>
<?= Html::beginForm(['product/cart', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
<div class="row">
	<div class="col-lg-8">
		<div class="row">
			<div class="col-lg-4">
				<label for="country">Select your destination country: </label>
			</div>
			<div class="col-lg-8">
				<div class="input-group dropdown">
					<?= Html::dropDownList('country',$selected['country'],ArrayHelper::map(Country::find()->all(),'code','name'),
						['class' => 'btn btn-default dropdown-toggle', 'required' => 'required', 'id' => 'country']) 
					?>
				</div>
			</div>
			<div class="col-lg-4">
				<label for="country">Insert promotion code: </label>
			</div>
			<div class="col-lg-6">
				<div class="input-group">
					<?= Html::input('text','promocode',$selected['promo'],['class' => 'form-control','placeholder' => 'promocode']) ?>
					<span class="input-group-btn">
						<?= Html::submitInput('Validate and Calculate Shipping', ['class' => 'btn btn-primary', 'name' => 'validate']) ?>
					</span>
				</div><!-- /input-group -->
			</div>
<?= Html::endForm(); ?>
			<div class="col-lg-2">
<?= Html::beginForm(['product/removepromo', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
				<?= Html::submitButton('Remove', ['class' => 'btn btn-primary btn-block', 'name' => 'remove']) ?>
<?= Html::endForm(); ?>
			</div>
			<div class="col-lg-4">
				<label>Retail Price </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_retail'] ?></p>
			</div>
			<div class="col-lg-4">
				<label>Selling Price </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_totalBefore'] ?></p>
			</div>
			<div class="col-lg-4">
				<label for="country">Discount</label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_discount'] ?></p>
			</div>
			<div class="col-lg-4">
				<label for="country">Subtotal </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_totalAfter'] ?></p>
			</div>
			<div class="col-lg-4">
				<label for="country">Shipping Fee </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_shipping'] ?></p>
			</div>
			<div class="col-lg-4">
				<label for="country">Total </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_total'] ?></p>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-4">
		<?php if($message['message'] && $message['error']): ?>
			<div class="alert alert-warning" role="alert"><?= $message['message'] ?></div>
		<?php elseif($message['message'] && !$message['error']): ?>
			<div class="alert alert-success" role="alert"><?= $message['message'] ?></div>
		<?php endif ?>
	</div>
</div>
<div class="row">
<div class="col-lg-12 pull-left">
<?= Html::beginForm(['product/cart', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::submitInput('Checkout', ['class' => 'btn btn-primary', 'name' => 'checkout']) ?>
<?= Html::endForm(); ?>
</div>
</div>

<br/>
<div class="row">
<div class="col-md-12">
<?= Html::beginForm(['product/removeall', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::submitButton('Remove All', ['class' => 'btn btn-primary btn-block', 'name' => 'remove']) ?>
<?= Html::endForm(); ?>
</div>
</div>