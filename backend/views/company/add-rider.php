<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;

	$this->title = 'Add Rider';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company List'), 'url' => ['/company/index']];
	$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($company, 'uid')->widget(Select2::classname(), [
    'data' => $deliveryman,
    'options' => ['placeholder' => 'Select a deliveryman ...']])->label('Deliveryman');
    ?>
<?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end();?>