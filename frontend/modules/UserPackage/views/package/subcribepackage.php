<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

	$this->title = "Subscribe Food Package";
	
?>
<div class="container">
	<h1><?= Html::encode($model->Name) ?></h1>
	<p>Price: RM <?= Html::encode($model->Price) ?></p>
	<?php $form = ActiveForm::begin(['action' => ['/UserPackage/package/postitem'],'method' => 'post',]);?>
		<div class="row">
			<?= $form->field($userPackageDetail,'quantity')->textInput()?>
			
			<?= $form->field($userPackage,'type')->dropDownList($subscribeList) ?>
			<?= $form->field($userPackageDetail,'fid')->hiddenInput(['value' => $model->Food_ID])->label(false)?>
			<?php foreach($model->foodSelection as $i =>$selection) :?>
				<div class="col-md-3">
				<p><?= Html::encode($selection->Name) ?></p>

				<?php  echo $form->field($userPackageSelectionType, '['.$i.']quantity', [
			        'addon' => ['prepend' => ['content'=>"<input id='userPackageSelectionType' , name='UserPackageSelectionType[$i][check]', type='checkbox'>"]],
			    ]);?>
			    <?= $form->field($userPackageSelectionType,'['.$i.']selectionitypeId')->hiddenInput(['value' => $selection->ID])->label(false)?>	
			    </div>
			<?php endforeach ;?>
		</div>
		<?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
	<?php ActiveForm::end();?>
</div>