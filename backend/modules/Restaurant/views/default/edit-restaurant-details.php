<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

  $this->title = 'Edit Restaurant Details';
  $this->params['breadcrumbs'][] = ['label' => 'Restaurant', 'url' => ['index']];
  $this->params['breadcrumbs'][] = $this->title;
  
?>

<?php $form = ActiveForm::begin();?>
    
    <?= $form->field($model, 'en_name')->textInput(['value'=>$value['en']])?>
    <?= $form->field($model, 'zh_name')->textInput(['value'=>$value['zh']]) ?>
    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>