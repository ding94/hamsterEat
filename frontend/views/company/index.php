<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
use common\models\Company\CompanyEmployees;
use common\models\User;

?>
<?php $url = \yii\helpers\Url::to(['/company/userlist']);?>

<?php $form = ActiveForm::begin(); ?>
<div class="container" style="background-color:#fff;">
<h1 style="font-size:30px;"><center>Employee Management</center></h1>
<div class="row" style="margin-top: 3%;">
	<div class="col-lg-5 col-lg-offset-3" >
		<?= $form->field($emplo, 'uid')->widget(Select2::classname(), [
		    'options' => ['placeholder' => 'Search for an user ...'],
		    'pluginOptions' => [
		        'allowClear' => true,
		        'minimumInputLength' => 6,
		        'language' => [
		            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
		        ],
		        'ajax' => [
		            'url' => $url,
		            'dataType' => 'json',
		            'data' => new JsExpression('function(params) { return {q:params.term}; }')
		        ],
		        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		        'templateResult' => new JsExpression('function(user) { return user.username; }'),
		        'templateSelection' => new JsExpression('function (user) { return user.username; }'),
		    ],
		])->label('Username');  ?>
	</div>
	<div class="col-md-2" style="margin-top: 2%;">
		<?= Html::submitButton('Add Employee', ['class' => 'raised-btn main-btn submit-resize-btn', 'name' => 'add-button']) ?>
	</div>
</div>
<?php ActiveForm::end(); ?>

<?php $users = CompanyEmployees::find()->where('cid=:id',[':id'=>$company['id']])->all(); ?>
<div class="col-lg-5 col-lg-offset-3" style="background-color: white;">
<h3>User Assigned in <?= $company['name']; ?> </h3>
	<table class="table table-hover">
		<?php foreach($users as $k => $value) : ?>
			<tr>
				<td><?= User::find()->where('id=:uid',[':uid'=>$value['uid']])->one()->username; ?></td>
				<td>Delete</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
</div>
