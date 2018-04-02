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
<h1 style="font-size:30px;"><center><?= Yii::t('company','Employee Management') ?></center></h1>
<div class="row" style="margin-top: 3%;">
	<div class="col-lg-5 col-lg-offset-3" >
		<?= $form->field($emplo, 'uid')->widget(Select2::classname(), [
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
		            'data' => new JsExpression('function(params) { return {q:params.term}; }')
		        ],
		        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
		        'templateResult' => new JsExpression('function(user) { return user.username; }'),
		        'templateSelection' => new JsExpression('function (user) { return user.username; }'),
		    ],
		])->label(Yii::t('company','Username'));  ?>
	</div>
	<div class="col-md-2" style="margin-top: 2%;">
		<?= Html::submitButton(Yii::t('company','Add Employee'), ['class' => 'raised-btn main-btn submit-resize-btn', 'name' => 'add-button']) ?>
	</div>
</div>
<?php ActiveForm::end(); ?>


<div class="col-lg-5">
<h3><?= Yii::t('company','User Assigned in')?> <?= $company['name']; ?> </h3>
	<table class="table table-hover">
		<tr>
			<th><?= Yii::t('company','Serial ID')?></th>
			<th><?= Yii::t('company','Username')?></th>
			<th></th>
		</tr>
		<?php foreach($approved as $k => $value) : ?>
			<tr>
				<td><?= $k+1; ?></td>
				<td><font> <?= User::find()->where('id=:uid',[':uid'=>$value['uid']])->one()->username; ?> </font></td>
				<td><?= Html::a(Yii::t('common','Reject'), ['/company/reject-employee', 'id'=>$value['id']], ['class'=>'raised-btn btn-danger']);?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>

<div class="col-lg-5">
<h3><?= Yii::t('company',"User haven't approved")?></h3>
	<table class="table table-hover">
		<tr>
			<th><?= Yii::t('company','Serial ID')?></th>
			<th><?= Yii::t('company','Username')?></th>
			<th></th>
		</tr>
		<?php foreach($rejected as $k => $value) : ?>
			<tr>
				<td><?= $k+1; ?></td>
				<td><font> <?= User::find()->where('id=:uid',[':uid'=>$value['uid']])->one()->username; ?> </font></td>
				<td>
					<?= Html::a(Yii::t('common','Approve'), ['/company/approve-employee', 'id'=>$value['id']], ['class'=>'raised-btn btn-success']);?>
					<?= Html::a(Yii::t('common','Remove'), ['/company/removeemployee', 'id'=>$value['id']], ['class'=>'raised-btn btn-danger']);?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>

<div class="col-lg-5 col-lg-offset-3">
<?php echo \yii\widgets\LinkPager::widget([
      'pagination' => $pagination,
    ]); ?>
</div>
</div>
