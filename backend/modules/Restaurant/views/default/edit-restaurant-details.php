<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

  $this->title = 'Edit Restaurant Details';
  $this->params['breadcrumbs'][] = ['label' => 'Restaurant', 'url' => ['index']];
  $this->params['breadcrumbs'][] = $this->title;
  
?>

<?php $form = ActiveForm::begin();?>
    <div class="row">
    	<div class="col-md-4">
    		<?= $form->field($model, 'en_name')->textInput(['value'=>$value['en']])?>
    	</div>
    	<div class="col-md-4">
    		<?= $form->field($model, 'zh_name')->textInput(['value'=>$value['zh']])?>
    	</div>
    </div>

    <?= $form->field($restaurant, 'Restaurant_LicenseNo')->textInput()?>
    <?= $form->field($restaurant, 'Restaurant_Street')->textInput()?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?> 
        <?= Html::a('Back',['/restaurant/default/index'],['class'=>'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>