<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use frontend\assets\FoodServiceAsset;

FoodServiceAsset::register($this);

$status = [1=>'Open',0=>'Closed'];

Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('food','Please Provide Reason').'</h2>',
      'id'     => 'add-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('common','Close').'</a>',
]);
Modal::end();

?>

<div class="container">
	<div class="col-sm-2">
		
	</div>
	<div class="col-sm-10">
		
		<div class="food-status">
			<div class="row">
				<div class="col-xs-9">
					<?= $model->name ;?>
					Currently <?= $status[$model->Status];?>
				</div>
				<div class="col-xs-3">
					<?php if($model->Status == 1):?>
						 <?= Html::a('Turn Off', ['providereason', 'id'=>$model['Food_ID'],'rid'=>$rid,'item'=>2], ['class'=>'resize-btn raised-btn btn-success','data-toggle'=>'modal','data-target'=>'#add-modal'])?>
					<?php else :?>
						<?= Html::a('Turn On', ['active', 'id'=>$model['Food_ID'], ['class'=>'resize-btn raised-btn btn-success']])?>
					<?php endif ;?>
				</div>
			</div>
		</div>
		<div class="selection-status">
			<div class="row">
				<?php foreach($model->selection as $selection) :?>
					<div class="col-xs-9">
						<?= $selection->Name ;?>
						Currently <?= $status[$selection->Status];?>
					</div>
					<div class="col-xs-3">
						<?php if($selection->Status == 1):?>
							 <?= Html::a('Turn Off',['providereason','id'=>$selection->ID,'item'=>3,'rid'=>$rid] , ['class'=>'resize-btn raised-btn btn-success','data-toggle'=>'modal','data-target'=>'#add-modal'])?>
						<?php else :?>
							 <?= Html::a('Turn On', [ 'selectionactive','id'=>$selection->ID, ['class'=>'resize-btn raised-btn btn-success']])?>
						<?php endif ;?>
					</div>
				<?php endforeach ;?>
			</div>
		</div>
	</div>
</div>