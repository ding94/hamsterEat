<?php

use kartik\grid\GridView;

$this->title = 'All Order List';
$this->params['breadcrumbs'][] = $this->title;

 	echo GridView::widget([
        'dataProvider'=>$model,
        'filterModel'=>$searchModel,
        'pjax'=>true,
        'striped'=>false,
        'hover'=>true,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'panel'=>[
            'type'=>'success',
            'layout'=>'{export} {toggleData}',
        ],
        'columns' =>[
        	[
			    'class' => 'kartik\grid\SerialColumn',
			    'contentOptions' => ['class' => 'kartik-sheet-style'],
			    'width' => '36px',
			    'header' => '',
			    'headerOptions' => ['class' => 'kartik-sheet-style']
			],
			[
				'attribute' => 'Delivery_ID',
				'width' => '15px',
			],
			[
				'attribute' => 'User_Username',
				'filter' => $arrayData['user'],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Any User'],
			    'format' => 'raw'
			],
			[
				'attribute' => 'Orders_PaymentMethod',
				'filter' => ['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'],
				'format' => 'raw'
			],
			[
				'attribute' =>'Orders_Status',
				
				'filter' => $arrayData['status'],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Any Status'],
			    'value'=>function($model){
			    	return $model->status;
			    }
			],
			[
			
				'attribute' => 'Orders_DateTimeMade',
				'format' => 'datetime',
				'filterType' => GridView::FILTER_DATE,

			],
			
        ],
    ]);
?>