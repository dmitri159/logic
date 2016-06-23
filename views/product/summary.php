<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\Country;
?>
<h1>Summary</h1>
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
<div class="row">
	<div class="col-lg-8">
		<div class="row">
			<div class="col-lg-4">
				<label for="country">Selected country: </label>
			</div>
			<div class="col-lg-8">
				<p><?php $country = Country::findOne($cart_total['_country']); echo $country['name']; ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<label for="country">Inserted promotion code: </label>
			</div>
			<div class="col-lg-8">
				<p><?= $cart_total['_promocode'] ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<label>Retail Price </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_retail'] ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<label>Selling Price </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_totalBefore'] ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<label for="country">Discount</label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_discount'] ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<label for="country">Subtotal </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_totalAfter'] ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<label for="country">Shipping Fee </label>
			</div>
			<div class="col-lg-8">
				<p>MYR<?= $cart_total['_shipping'] ?></p>
			</div>
		</div>
		<div class="row">
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
<div class="col-lg-12 pull-left">
<?= Html::beginForm(['product/pay'], 'post', ['enctype' => 'multipart/form-data']) ?>
<?= Html::submitInput('Pay', ['class' => 'btn btn-primary', 'name' => 'checkout']) ?>
<?= Html::endForm(); ?>
</div>
</div>

<br/>
<div class="row">
<div class="col-md-12">
</div>
</div>