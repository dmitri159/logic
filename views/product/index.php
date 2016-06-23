<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<h1>Products</h1>
<ul>

<?php foreach ($products as $product): ?>
    <li>
    	<?= Html::beginForm(['product/index', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
	    <?php	$img = Url::to('@web/upload'.$product->p_img); ?>
    	
        <?= $product->p_name ?> _ <strike><?= $product->p_price ?></strike> - <?= $product->p_discount ?> - <?= Html::img($img,['class' => 'img-responsive', 'width' => '50%', 'height' => '50%']) ?>
        <div class="dropdown">
	    	<?= Html::dropDownList('quantity'.$product->id,null,
	    		['1' => '1', '2' => '2', '3' => '3'],
	    		[]) 
	    	?>
		</div>
        <div class="form-group">
            <?= Html::submitButton('Add To Cart', ['class' => 'btn btn-primary','value' => $product->id, 'name' => 'product']) ?>
        </div>
        <?php Html::endForm(); ?>
    </li>
<?php endforeach; ?>
<?php print_r($cart_total); ?>

</ul>