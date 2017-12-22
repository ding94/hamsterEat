<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

  $this->title = 'Order Detail';
?>
  
<?php
	foreach ($orderitem as $k => $order) {
		echo "<H3 style=background-color:#ffcc99;height:35px;>Order ID : ".$order['Order_ID']."</H4>";
	    echo DetailView::widget([
		    'model' => $order,
		    'attributes' => [
		    	'food.restaurant.Restaurant_Name', // food.restaurant.Restaurant_Name = (Current Model get).(First get Model inside that Model get).(attribute)
		    	[
		    		'attribute' => 'food.Name',
		    		'label' => 'Food Name',
		    		'contentOptions' => ['style' => 'width:75%;'],
		    	],
		    	[
		    		'attribute' =>'food_selection_Name',
		    		'label' => 'Food Selection',
		    		'value' => function ($model){ return $model->getFood_selection_name($model); }
		    	],
		    	/*[
		    		'attribute' =>'food.Nickname',
		    		'label' => 'Food Nickname',
		    		'contentOptions' => ['style' => 'background-color:#ffcc99;'],
		    	],
		    	[
		    		'attribute' =>'order_selection.food_selection.Nickname',
		    		'label' => 'Selection Nickname',
		    		'contentOptions' => ['style' => 'background-color:#ffcd99;'],
		    	],*/
		    	'OrderItem_Quantity',
		    	[
		    		'attribute' => 'order.Orders_TotalPrice',
		    		'label' => 'Total Order Price (RM)',
		    	],
		    	'OrderItem_Status',
		    	[
		    		'attribute' => 'address.location',
		    	],
		    	[
		    		'attribute' => 'address.postcode',
		    	],
		    	[
		    		'attribute' => 'address.area',
		    	],

			],
		]);
	}
?>