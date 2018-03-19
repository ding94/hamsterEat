<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

	$this->title = "Update ".$model->name;
	$this->params['breadcrumbs'][] = ['label' => 'Permission', 'url' => ['permission']];
	$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin();?>
	<?= $form->field($model, 'name')->textInput() ?>
	<?= $form->field($model, 'description')->textInput() ?>
	<?= $form->field($model, 'data')->dropDownList($list) ?>
	<?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end();?>