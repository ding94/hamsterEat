<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

	$this->title = 'Force Change';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Force Account History'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
	
?>

<?php $form = ActiveForm::begin(['id' => 'force-form-inline', 'action' => ['accountforce/submit-data']]); ?>
	<?php echo $form->field($model, 'uid')->widget(Select2::classname(), [
	    'data' => $user,
	    'options' => ['placeholder' => 'Select a user ...'],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
	]); ?>
	<?= $form->field($model, 'amount') ?>
	<?= $form->field($model, 'reason') ?>
	 <?=Html::submitButton('Submit', ['class' => 'btn btn-danger',]);?>
<?php ActiveForm::end(); ?>

