<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

    $this->title = "Today's Problem Orders";
    
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        	'Order_ID',
        	[
        		'attribute' => 'userfullname',
        		'value' => 'order.User_fullname',
        		'label' => "User Fullname",
        	],
        	[
        		'attribute' => 'usercontact',
        		'value' => 'order.User_contactno',
        		'label' => "User Contact",
        	],
        	[
        		'attribute' => 'food',
        		'value' => "food.Name",
        		'label' => "Food's Name",
        	],
        	[
        		'attribute' => 'food_selection',
        		'value' => "order_selection.food_selection.Name",
        		'label' => "Selection Name",
        	],

        	



        ],
    ])?>