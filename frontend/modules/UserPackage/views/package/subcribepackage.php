<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Json;
use kartik\widgets\DatePicker;

	$this->title = "Subscribe Food Package";
	
?>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<h1><?= Html::encode($food['Name']) ?></h1>
			<h4>Total Price: RM <?= Html::encode($packageDetail['totalPrice'])?></h4>
			<h4>Total Quantity : <?= Html::encode($packageDetail['quantity'])?></h4>

			<?php $form = ActiveForm::begin(['action' => ['/UserPackage/package/postitem'],'method' => 'post',]);?>
			
				<input  type="hidden" name="packageDetail" value=<?php echo Json::encode($packageDetail)?>>
				<h4>Item Selected</h4>
				<?php foreach($food['foodSelection'] as $i=>$data) : ?>
					<ul>
						<li><?php echo $data['Name']?></li>
						<?= $form->field($selectionType,'['.$i.']selectionitypeId')->hiddenInput(['value' => $data['ID']])->label(false)?>
					</ul>	
				<?php endforeach ;?>
				<label class="control-label">Select Date to delivery</label>
                <?php
                  echo DatePicker::widget([
                    'name' => 'dateTime',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'value' => $dateTime,
                    'pluginOptions' => [
                        'format' => 'yyyy/mm/dd/',
                        'multidate' => true,
                        'multidateSeparator' => ',',
                        'startDate' => date('Y/m/d',strtotime("+2 day")),
                    ]
                  ]);
                ?>
				<?= Html::submitButton('Confirm Subscribe', ['class' => 'btn btn-primary']) ?>
			<?php ActiveForm::end();?>
		</div>
	</div>
</div>