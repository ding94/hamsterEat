<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>
<div class="container">
	Enter Nick Name For The Order
	<div class="row">
		<?php
		$form = ActiveForm::begin(['id' => 'a2Nick','action'=>['generate-nick']]);
		$cookies = Yii::$app->request->cookies;
		
		$nick = $cookies->getValue('cartNickName');
		
		for($i=0;$i < $nick['quantity']; $i++):
			echo $form->field($model, '['.$i.']nickname', ['enableClientValidation' => false]);
			echo $form->field($model, '['.$i.']tid')->hiddenInput(['value'=>$nick['id']])->label(false);
	 	endfor;
	 	echo Html::submitButton("Submit", ['class' => 'raised-btn main-btn']);
	 	echo Html::a("Skip",'#',['class'=>'raised-btn secondary-btn']);
	 	ActiveForm::end();
	?>
	</div>
</div>
	
	