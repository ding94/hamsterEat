<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

	$this->title = 'Register new Comapny';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company List'), 'url' => ['/company/index']];
	$this->params['breadcrumbs'][] = $this->title;
?>


	<?php $form = ActiveForm::begin();?>
    <?= $form->field($company ,'name')->textInput()->label('Company Name')?>
    <?= $form->field($company ,'username')->textInput()->label('Owner username')?>
    <?= $form->field($company ,'license_no')->textInput()?>
    <?= $form->field($company ,'address')->textInput()->label('Company address')?>


    <?= $form->field($company, 'postcode')->widget(Select2::classname(), [
        'data' => $postcode,
        'options' => ['placeholder' => 'Select a postcode ...','id'=>'postcode-select']])->label('Postcode');
        ?>

    <?= $form->field($company,'area')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'options' => ['id'=>'area-select','placeholder' => 'Select an area ...'],
            'pluginOptions'=>[
                'depends'=>['postcode-select'],
                'url'=>Url::to(['/company/get-area'])
            ],
            ]); ?>

    	<div class="form-group">
	        <?= Html::submitButton('Register', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Back', ['/company/index'], ['class'=>'btn btn-primary']) ?>
	   </div>
	<?php ActiveForm::end();?>
