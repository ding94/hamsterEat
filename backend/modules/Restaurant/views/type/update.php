<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\FoodAsset;

FoodAsset::register($this);

$this->title = "Edit Food Selection";
$this->params['breadcrumbs'][] = ['label' => 'Food Detail', 'url' => ['/restaurant/food','id'=>0]];
$this->params['breadcrumbs'][] = ['label' => 'Food Type And Selection', 'url' => ['/restaurant/type/index','id'=>$selection->Food_ID]];
$this->params['breadcrumbs'][] = $this->title;

	$form = ActiveForm::begin(); 
		foreach($allname as $i=>$name):
			if($name->isNewRecord):
				echo $form->field($name,'['.$i.']translation', ['enableClientValidation' => false])->textInput()->label($name->language." name");
			else:
				echo $form->field($name,'['.$i.']translation')->textInput()->label($name->language." name");
			endif;
		endforeach;?>
		<div class="orgin-price">
			<?=$form->field($selection,'BeforeMarkedUp')->textInput();?>
		</div>
		<div class="markup-price">
			<?=$form->field($selection,'Price')->textInput();?>
		</div>
		<?php
		echo Html::submitButton("Edit" ,['class' =>  'btn btn-success']); 
	 ActiveForm::end();?>
