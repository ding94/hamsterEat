<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\FoodOnOffAsset;

$this->title = Yii::t('food','Edit Food Name');
FoodOnOffAsset::register($this);
?>

<div class="container">
	<div class="food-service-header">
        <div class="food-service-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
    	<div class="col-sm-2">
    		<ul id="food-onoff-nav" class="nav nav-pills nav-stacked">
	            <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i>".Yii::t('common','Back'),['food/menu','rid' => $rid,'page'=>'menu'])?>
	            	
	            </li>
	        </ul>
    	</div>
	    <div class="col-sm-10 food-onoff-content">
	    	<?php $form = ActiveForm::begin(); ?>
	    	<table class="table table-bordered">
	    		<thead>
					<tr><th colspan="3" class="center">Food Name Edit</th></tr>
				</thead>
				<tbody>
					<tr>
						<?php foreach($name as $i=>$single):?>
						<td><?= $form->field($single,'['.$i.']translation')->label("Food ".$single->language." name")?>
						<?= $form->field($single,'['.$i.']language')->hiddenInput()->label(false)?></td>
						<?php endforeach;?>
					</tr>
					<?php if(!empty($arrayData)):?>
					<?php foreach($arrayData['type'] as $index=>$type):?>
						
						<?php if(!empty($arrayData['selection'][$index])):?>
					<tr>
						<td rowspan=<?php echo count($arrayData['selection'][$index])+1?>>
						<?php foreach($type as $i=>$single):?>
							<?= $form->field($single,'['.$index.']['.$i.']translation')?>
							<?= $form->field($single,'['.$index.']['.$i.']language')->hiddenInput()->label(false)?>
						<?php 	endforeach;?>
						</td>
					</tr>
						<?php foreach($arrayData['selection'][$index] as $k => $selection):
						?>
						<tr>	
							<?php foreach($selection as $s=>$single):?>
								<td>
									<?= $form->field($single,'['.$index.']['.$k.']['.$s.']translation')?>	
									<?= $form->field($single,'['.$index.']['.$k.']['.$s.']language')->hiddenInput()->label(false)?>	
								</td>
							<?php endforeach;?>
						</tr>	
						<?php endforeach;?>
						<?php endif;?>
					<?php endforeach;?>
					<?php endif;?>
				</tbody>
	    	</table>
	    	<?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'raised-btn main-btn submit-resize-btn']) ?>
	    	<?php ActiveForm::end(); ?>
	    </div>
</div>