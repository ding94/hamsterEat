<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use frontend\assets\FoodOnOffAsset;
use kartik\widgets\Select2;
use yii\helpers\Url;

FoodOnOffAsset::register($this);

$status = [1=>'Open',0=>'Closed'];

Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('food','Please Provide Reason').'</h2>',
      'id'     => 'add-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('common','Close').'</a>',
]);
Modal::end();

$this->title = "Food ".$model->name." Service";
?>

<div class="container">
	<div class="food-service-header">
        <div class="food-service-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
		<div class="col-sm-2">
			<div class="dropdown-url">
			<?php
				$url = Url::to(['/Food/default/menu','rid'=>$rid]);
                echo Select2::widget([
	                'name' => 'url-redirect',
	                'hideSearch' => true,
	                'data' => [$url=>"Back"] ,
	                'options' => [
	                    'placeholder' => Yii::t('common','Go To ...'),
	                    'multiple' => false,
	                ],
	                'pluginEvents' => [
	                    "change" => 'function (e){
	                      	location.href =this.value;
	                    }',
	                ]
	            ]);
	        ;?>
			</div>
			<div class="nav-url">
	            <ul id="food-onoff-nav" class="nav nav-pills nav-stacked">
	                <li role="presentation">
	                   	<a class="btn-block" href=<?php echo $url?>><i class="fa fa-chevron-left"></i> Back</a>
	             	</li>
	            </ul>
	        </div>
		</div>
		<div class="col-sm-10 food-onoff-content">
			<table class="table">
				<thead>
					<tr>
						<th colspan="4" class="center">Food Name : <?= $model->Name?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">Current Status :<?= $status[$model->Status] ?></td>
						
						<td class="right <?php echo $model->Status == 0 ? "" : "none"?>">
							<?php if ($model->Status == 0) : 
								echo Html::a(Yii::t('food','Delete').'<i class="fa fa-times" aria-hidden="true"></i>',['/Food/default/delete','id'=>$model->Food_ID],['class' => 'raised-btn delete-btn btn-danger right','data' => ['confirm' => Yii::t('food','Are you sure you want to permenant delete this item?'),'method' => 'post',]]);
							endif;?>
						</td>
						<td class="width-10">
							<?php 
							if($model->Status == 1):
								echo Html::a('Turn Off Food', ['providereason', 'id'=>$model['Food_ID'],'rid'=>$rid,'item'=>2], ['class'=>'resize-btn raised-btn btn-danger','data-toggle'=>'modal','data-target'=>'#add-modal']);
							 else :
								echo Html::a('Turn On Food', ['active', 'id'=>$model['Food_ID'],'rid'=>$rid,'type'=>1], ['class'=>'resize-btn raised-btn btn-success']);
							endif ;
							?>
						</td>
					</tr>
					<?php foreach($selectiondata as $name=>$type) :?>		
					<tr class="border">
						<th colspan = "4" class="center">Food Selection Type : <?= $name?></th>
					</tr>
					<?php foreach($type as $selection) :?>
					<tr>
						<td class="width name" data-th="Selection Name"><?= $selection->originName ?></td>
						<td colspan=<?php echo $selection->Status == 0 ? 1 : 2?>>Current Status : <?= $status[$selection->Status] ?></td>
						<?php if($selection->Status == 0) : ?>
						
						<?php endif;?>
						<td class="width-10">
						<?php 
							if($selection->Status == 1):
								echo Html::a('Turn Off Selection',['providereason','id'=>$selection->ID,'item'=>3,'rid'=>$rid] , ['class'=>'resize-btn raised-btn btn-danger right','data-toggle'=>'modal','data-target'=>'#add-modal']);
							elseif($model->Status == 1) :
								echo Html::a('Turn On Selection',['active','id'=>$selection->ID,'rid'=>$rid,'type'=>0] , ['class'=>'resize-btn raised-btn btn-success right']);
							endif; 
						;?>
						</td>
					</tr>
					<?php endforeach ;?>
				</tbody>
				<?php endforeach ;?>
			</table>
		</div>
	</div>
</div>