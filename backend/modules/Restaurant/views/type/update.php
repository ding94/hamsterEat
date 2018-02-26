<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Edit Food Selection";
$this->params['breadcrumbs'][] = ['label' => 'Food Detail', 'url' => ['/restaurant/food','id'=>0]];
$this->params['breadcrumbs'][] = ['label' => 'Food Type And Selection', 'url' => ['/restaurant/type/index','id'=>$selection->Food_ID]];
$this->params['breadcrumbs'][] = $this->title;
?>
	<?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<?php foreach($type->allName as $i=>$name):?>
			<div class="col-md-6">
				<?= $form->field($name,'['.$i.']translation')->textInput()->label($name->language." name");?>
			</div>
		<?php endforeach;?>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<?= $form->field($type,'Min')->textInput();?>
		</div>
		<div class="col-xs-6">
			<?= $form->field($type,'Max')->textInput();?>
		</div>
	</div>
	<div class="row">
		<?php foreach($selection->allName as $i=>$name):?>
			<div class="col-md-6">
				<?= $form->field($name,'['.$i.']translation')->textInput()->label($name->language." name");?>
			</div>
		<?php endforeach;?>
	</div>
	
	<?php ActiveForm::end();?>
