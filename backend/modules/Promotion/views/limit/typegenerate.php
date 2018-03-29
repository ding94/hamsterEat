<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\TouchSpin;

$this->title = "Generate List Of Promotion With Maximun Limit :".$promotion->food_limit;
$this->params['breadcrumbs'][] = ['label' => 'Promotion Index', 'url' => ['/promotion/setting/index']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();
?>
<div class="row">
	<?php foreach($data as $i=>$value):?>
		<div class="col-sm-4">
			<?php 
				echo Html::activeHiddenInput($model,'['.$i.']pid',['value'=>$promotion->id]);
				echo Html::activeHiddenInput($model,'['.$i.']tid',['value'=>$value->id]);
				echo $form->field($model,'['.$i.']food_limit')->widget(TouchSpin::classname(),[
					'pluginOptions' => [
				        'buttonup_class' => 'btn btn-primary', 
				        'buttondown_class' => 'btn btn-info', 
				        'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
				        'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
				    ]
				])->label($value->name." Limit");
			?>
		</div>
	<?php endforeach;?>
</div>
 
<?php echo  Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
ActiveForm::end();
?>