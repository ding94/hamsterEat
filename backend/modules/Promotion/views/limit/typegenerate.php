<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\TouchSpin;

$this->title = "Generate List Of Promotion ";
$this->params['breadcrumbs'][] = ['label' => 'Promotion Index', 'url' => ['/promotion/setting/index']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();
?>
<div class="row">
	<?php foreach($model as $i=>$single):?>
		<div class="col-sm-4">
			<?php 
				echo Html::activeHiddenInput($single,'['.$i.']pid',['value'=>$promotion->id]);
				if(empty($data))
				{
					echo Html::activeHiddenInput($single,'['.$i.']tid',['value'=>0]);
				}
				else
				{
					
					switch ($promotion->type_promotion) {
						case 2:
							$dataid = $data[$i]->Restaurant_ID;
							$dataname = $data[$i]->originName;
							break;
						case 3:
							$dataid = $data[$i]->Food_ID;
							$dataname = $data[$i]->originName;
							break;
						case 4:
							$dataid = $data[$i]->id;
							$dataname = $data[$i]->name;
							break;
						default:
							# code...
							break;
					}
					
					echo Html::activeHiddenInput($single,'['.$i.']tid',['value'=>$dataid]);
				}
				$name = empty($data) ? "Default " : $dataname;
				if($single->isNewRecord):
				echo $form->field($single,'['.$i.']food_limit')->widget(TouchSpin::classname(),[
						'pluginOptions' => [
							'initval'=>0,
					        'buttonup_class' => 'btn btn-primary', 
					        'buttondown_class' => 'btn btn-info', 
					        'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
					        'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
					    ]
					])->label($name." Limit");
				else:
					echo $form->field($single,'['.$i.']food_limit')->widget(TouchSpin::classname(),[
						'pluginOptions' => [
					        'buttonup_class' => 'btn btn-primary', 
					        'buttondown_class' => 'btn btn-info', 
					        'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
					        'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
					    ]
					])->label($name." Limit");
				endif;
			?>
		</div>
	<?php endforeach;?>
</div>
 
<?php echo  Html::submitButton($model[0]->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model[0]->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
ActiveForm::end();
?>