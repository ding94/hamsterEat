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
    	</div>
    </div>
    <div class="col-sm-10 food-onoff-content">
    	<?php $form = ActiveForm::begin(); ?>
    	<table class="table">
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
				<?php foreach($arrayData['type'] as $index=>$type):?>
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
					
				
				<?php	endforeach;?>
			</tbody>
    	</table>
    	<?= Html::submitButton(Yii::t('common','Submit'), ['class' => 'raised-btn main-btn submit-resize-btn']) ?>
    	<?php ActiveForm::end(); ?>
    </div>
</div>