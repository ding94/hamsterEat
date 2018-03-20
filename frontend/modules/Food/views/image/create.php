<?php

use yii\helpers\Html;
use frontend\assets\AddFoodAsset;
use kartik\widgets\FileInput;
use yii\helpers\Url;

AddFoodAsset::register($this);
$this->title =  Yii::t('food','Add Food Image');
?>

<div class="container">
    <div class="checkout-progress-bar">
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?= Yii::t('common','Food') ?></span>
        </div>
        <span class="bar done"></span>
        <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?=Yii::t('common','Selection'); ?></span>
        </div>
        <span class="bar done"></span>
        <div class="circle active">
           <span class="label"><i class="fa fa-picture-o"></i></span>
           <span class="title"><?= Yii::t('common','Image') ?></span>
        </div>
    </div> 
</div>
<div class="container">
	<div class=" food-container food-header">
       	<div class="food-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
    	<?php 
	    	echo FileInput::widget([
			    'name' => 'foodimg',
                'language'=>'en',
                'options'=>[
                    'multiple'=>true,
               	],
			    'pluginOptions' => [
			    	'initialPreviewAsData'=>true,
			    	'initialPreview' => $image,
                    'initialPreviewConfig' => $caption,
			    	'showRemove' => false,
			    	'initialPreviewAsData'=>true,
			    	'overwriteInitial'=>false,
			        'uploadUrl' => Url::to(['upload']),
			        'uploadExtraData' => [
			            'id' => $id,
			        ],
			        'maxFileCount' => 3,
                    'pluginLoading' => true,
                    'allowedFileExtensions' => ['jpg','png','jpeg'],
			    ]
			]);
    	?>
    	<br>
    	<?= Html::a('Finish',['/Food/default/menu','rid'=>$rid],['class'=>'raised-btn main-btn']); ?>
	</div>
</div>