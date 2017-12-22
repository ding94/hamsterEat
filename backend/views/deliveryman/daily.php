<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;

	$this->title = 'Delivery Man Daily Status';
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-2">
		<?php
			echo DatePicker::widget([
		    'name' => 'chose Date',
		     'removeButton' => false,
		    'pluginOptions' => [
		        'autoclose'=>true,
		        'format' => 'yyyy-mm-d'
		    ],
		    'pluginEvents' => [
		    	'changeDate' => 'function (e){
	                var date = $("#w0").val();
	                var arr = date.split("-");
	               	location.href = "/index.php?r=deliveryman/daily-signin&month="+arr[0]+"-"+arr[1]+"&day="+arr[2];
	             }',
		    ],
		]);
		?>
	</div>
</div>

<?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
        	[
        		'label' => 'User name',
        		'attribute' => 'user.username',
        	],
        	[
        		'label' => 'Sign In',
        		'attribute' => 'day',
        		'value' => function($model) use($day)
	        	{
	        		return $model->getTodaySign($model->day,$day);
	        	},
        	],	
        ],
    ]); ?>

