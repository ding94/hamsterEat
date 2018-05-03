<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\bootstrap\Modal;
use common\models\User;
use iutbay\yii2fontawesome\FontAwesome as FA;

    $this->title = 'Order Chance';
    $this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('Add Order Chance',['/orders/add-chance'],['class'=>'btn btn-primary']); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
        	'id',
        	[
        		'attribute' => 'uid',
        		'label' => 'Username',
        		'value' => function($model){
        			return User::findOne($model['uid'])->username;
        		}
        	],
        	'chances',
        	'start_time:datetime',
        	'end_time:datetime',
        ],
    ])
?>