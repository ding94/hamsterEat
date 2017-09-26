<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use yii\bootstrap\Modal;

	$this->title = 'Delivery Man Daily Status';
	$this->params['breadcrumbs'][] = $this->title;
?>

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

