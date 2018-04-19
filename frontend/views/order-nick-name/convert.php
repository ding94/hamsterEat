<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\assets\OrderNickNameAsset;

OrderNickNameAsset::register($this);
?>
<div id="convert-name">	
	
	<h4>Nick Name Will Stick In The Food Containner</h4>
	<?php

		$form = ActiveForm::begin(['id' => 'a2Nick','action'=>['generate-nick']]);
		echo Html::hiddenInput('convert-url',Url::to(['/order-nick-name/generate-nick']));
		$cookies = Yii::$app->request->cookies;
		
		$nick = $cookies->getValue('cartNickName');
		
		for($i=0;$i < $nick['quantity']; $i++):
			$a = $i+1;
			echo $form->field($model, '['.$i.']nickname', ['enableClientValidation' => false])->label("Nickname-".$a);
			echo $form->field($model, '['.$i.']tid')->hiddenInput(['value'=>$nick['id']])->label(false);
	 	endfor;
	 	echo Html::submitButton("Add", ['class' => 'add-to-name raised-btn main-btn']);
	 	echo Html::a("Skip",'#',['class'=>'skip-name raised-btn secondary-btn']);
	 	ActiveForm::end();
	?>
	
</div>
	
	