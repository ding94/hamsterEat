<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Edit Food Type";
$this->params['breadcrumbs'][] = ['label' => 'Food Detail', 'url' => ['/restaurant/food','id'=>0]];
$this->params['breadcrumbs'][] = ['label' => 'Food Type And Selection', 'url' => ['/restaurant/type/index','id'=>$type->Food_ID]];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin(); 
	foreach($allname as $i=>$name):
		if($name->isNewRecord):
				echo $form->field($name,'['.$i.']translation', ['enableClientValidation' => false])->textInput()->label($name->language." name");
			else:
				echo $form->field($name,'['.$i.']translation')->textInput()->label($name->language." name");
			endif;
	endforeach;
	echo  $form->field($type,'Min')->textInput();
	echo  $form->field($type,'Max')->textInput();
	echo Html::submitButton("Edit" ,['class' =>  'btn btn-success']); 
ActiveForm::end();
?>