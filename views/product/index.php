<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<h1>Products</h1>
<div class="row">
<?php $count = count($products);
    foreach ($products as $product): ?>
    <div class="col-lg-<?= 12/$count ?>">
    	<?= Html::beginForm(['product/index', 'id' => 'cart'], 'post', ['enctype' => 'multipart/form-data']) ?>
	    <?php	$img = Url::to('@web/upload'.$product->p_img); ?>
    	
        <?= Html::img($img,['class' => 'img-responsive', 'width' => '50%', 'height' => '50%']) ?>
        <h3><?= $product->p_name ?></h3>
        <h4>Retail <strike><strong>MYR<?= $product->p_price ?></strong></strike></h4>
        <h4>Selling <strong>MYR<?= $product->p_discount ?></strong></h4>
        <div class="form-group">
        <div class="input-group-btn">
            <?= Html::dropDownList('quantity'.$product->id,null,
                ['1' => '1', '2' => '2', '3' => '3','4' => '4', '5' => '5'],
                ['class' => 'btn btn-default']) 
            ?>
            <?= Html::submitButton('Add To Cart', ['class' => 'btn btn-primary','value' => $product->id, 'name' => 'product']) ?>
        </div>
            
        </div>
        <?php Html::endForm(); ?>
        </div>
<?php endforeach; ?>
</div>