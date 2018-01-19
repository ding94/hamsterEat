<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

?>
  
<?php
    echo DetailView::widget([
    'model' => $model,
    'attributes' => [
       [
    		'label' => 'Sub Total',
    		'format' => 'raw',
    		'value' => function($model){
    			return "<span class='pull-right'>".number_format($model->Orders_Subtotal, 2, '.', '')."</span>";
    		}
    	],
    	[
    		'label' => 'Delivery Charge',
    		'format' => 'raw',
    		'value' => function($model){
    			return "<span class='pull-right'>".number_format($model->Orders_DeliveryCharge, 2, '.', '')."</span>";
    		}
    	],
    	[
    		'label' => 'Early Discount',
    		'format' => 'raw',
    		'value' => function($model){
    			if($model->Orders_DiscountEarlyAmount == 0)
    			{
    				return "<span class='pull-right'>0.00</span>";
    			}
    			else
    			{
    				return "<span class='pull-right'> - ".number_format($model->Orders_DiscountEarlyAmount, 2, '.', '')."</span>";
    			}
    		}
    	],
    	[
    		'label' => 'Coupun Discount',
    		'format' => 'raw',
    		'value' => function($model){
    			if($model->Orders_DiscountTotalAmount == 0)
    			{
    				return "<span class='pull-right'>0.00</span>";
    			}
    			else
    			{
    				return "<span class='pull-right'> - ".number_format($model->Orders_DiscountTotalAmount, 2, '.', '')."</span>";
    			}
    			
    		}
    	],
       	[
    		'label' => 'Total',
    		'format' => 'raw',
    		'value' => function($model){
    			return "<span class='pull-right'> ".number_format($model->Orders_TotalPrice, 2, '.', '')."</span>";
    		}
    	],   
    ],
]);
?>