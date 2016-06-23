<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<h1>Products</h1>
<ul>

<?php foreach ($products as $product): ?>
    <li>
    	<?php ActiveForm::begin(['id' => 'product_'.$product->id, 'action' => ['product/index']]);  
	    	$img = Url::to('@web/upload'.$product->p_img); 
    	?>
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
        <?php ActiveForm::end(); ?>
    </li>
<?php endforeach; ?>
<?php echo \Yii::$app->cart->getCount(); ?>

</ul>