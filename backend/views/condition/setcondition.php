<?php 


use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = 'New Condition to Voucher';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Conditions List'), 'url' => ['/condition/index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<?php $form = ActiveForm::begin();?>
    <?= $form->field($voucon, 'code')->textInput() ?>
    <?= $form->field($voucon, 'condition_id')->dropDownList($condition);?>
    <?= $form->field($voucon, 'amount')->textInput()->label('Amount to Reach') ?>

    <div class="form-group">
	        <?= Html::submitButton('Generate', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Back', ['/condition/index'], ['class'=>'btn btn-primary']) ?>
	   </div>
<?php ActiveForm::end();?>