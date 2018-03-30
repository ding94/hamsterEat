<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

	$this->title = 'Add Employee';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company List'), 'url' => ['/company/index']];
	$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($employee, 'uid')->widget(Select2::classname(), [
		    'options' => ['placeholder' => 'Search for an user ...'],
		    'pluginOptions' => [
		        'allowClear' => true,
		        //'minimumInputLength' => 6,
		        'language' => [
		            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
		        ],
		        'ajax' => [
		            'url' => $url,
		            'dataType' => 'json',
		            'data' => new JsExpression('function(params) { return {rmanager:'.$company['owner_id'].',q:params.term}; }')
		        ],
		        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		        'templateResult' => new JsExpression('function(user) { return user.username; }'),
		        'templateSelection' => new JsExpression('function (user) { return user.username; }'),
		    ],
		])->label('Username');  ?>
<?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end();?>


<table class='table table-hover' style="background-color: white;margin:10px">
	<tr><td colspan="2"><h3>Employer of this company</h3></td></tr>
	<tr><td>#</td><td><b>Username</b></td></tr>
	<?php foreach ($list as $key => $value): ?>
		<tr>
			<td><?= $key+1 ?></td>
			<td><?= $value['user']['username']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>