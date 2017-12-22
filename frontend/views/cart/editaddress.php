<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\assets\UserAsset;
use kartik\widgets\ActiveForm;

UserAsset::register($this);
?>
<?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <?= $form->field($model, 'address')->dropDownList($address,['onchange' => 'js:change();',]); ?>
    <?= $form->field($model, 'recipient')->textInput(['value' => $first['recipient']]) ?>
    <?= $form->field($model, 'contactno')->textInput(['value'=>$first['contactno']]); ?>
    <?= $form->field($model, 'postcode')->textInput(['value'=>$first['postcode']]);  ?>
    <?= $form->field($model, 'city')->textInput(['value'=>$first['city']]); ?>
    <?= Html::submitButton('Save Address', ['class' => 'raised-btn main-btn', 'name' => 'insert-button']) ?>
<?php ActiveForm::end(); ?>


<script>
function change()
{
	$.ajax({
		url :"index.php?r=cart/getaddress",
		type: "get",
		data :{
			addr: document.getElementById("useraddress-address").value,
		},
		success: function (data) {
			var obj = JSON.parse(data);
			
			document.getElementById("useraddress-recipient").value = obj['recipient'];
			document.getElementById("useraddress-contactno").value = obj['contactno'];
			document.getElementById("useraddress-postcode").value = obj['postcode'];
			document.getElementById("useraddress-city").value = obj['city'];
		},
		error: function (request, status, error) {
			//alert(request.responseText);
		}

	});
}
</script>