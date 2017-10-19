<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use common\widgets\Alert;
use kartik\widgets\Select2;
use bootstrap\widgets\CActiveForm;
/* @var $this yii\web\View */
?>


<div class="container">
<div class="row">
<div class="col-md-7 col-md-offset-1 " >
<h1><?= Html::encode($this->title) ?></h1>
<?php $form = ActiveForm::begin();?>
		<?= $form->field($model, 'old_password')->passwordInput() ?>
		<?= $form->field($model, 'new_password')->passwordInput() ?>
		<?= $form->field($model, 'repeat_password')->passwordInput() ?>
    	<div class="form-group">
	        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>

</div>
</div>
</div>