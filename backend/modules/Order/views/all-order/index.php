<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = 'All Order List';
$this->params['breadcrumbs'][] = $this->title;
	
	Modal::begin([
        'id' => 'orderDetail',
        'header' => '<h4 class="modal-title">...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    Modal::end();
    Modal::begin([
        'id' => 'orderSpeed',
        'header' => '<h4 class="modal-title">...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    Modal::end();
    
    $this->registerJs("
        $('#orderDetail').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var title = button.data('title') 
            var href = button.attr('href') 
            modal.find('.modal-title').html(title)
            modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
            $.post(href)
                .done(function( data ) {
                    modal.find('.modal-body').html(data)
            });
        });

         $('#orderSpeed').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var title = button.data('title') 
            var href = button.attr('href') 
            modal.find('.modal-title').html(title)
            modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
            $.post(href)
                .done(function( data ) {
                    modal.find('.modal-body').html(data)
            });
        });
        
        $('#addressDetail').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var modal = $(this)
            var title = button.data('title') 
            var href = button.attr('href') 
            modal.find('.modal-title').html(title)
            modal.find('.modal-body').html('<i class=\"fa fa-spinner fa-spin\"></i>')
            $.post(href)
                .done(function( data ) {
                    modal.find('.modal-body').html(data)
            });
        });
    ");

   	$status = $arrayData['status'];

 	echo GridView::widget([
        'dataProvider'=>$model,
        'filterModel'=>$searchModel,
        'pjax'=>false,
        'striped'=>false,
        'hover'=>true,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'panel'=>[
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
                'label'=>'Username',
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
                'label'=>'Company',
                'attribute' => 'companyname',
                'value' => 'address.company.name',
                'filter' => $arrayData['company'],
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Any Company'],
                'format' => 'raw'
				
			],
			[
				'attribute' =>'Orders_Status',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => $status,
				'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Any Status'],
			    'value'=>function($model)use($status){
			    	return $status[$model->Orders_Status];
			    }
			],
			[
				'attribute' => 'Orders_DateTimeMade',
				'format' => 'datetime',
				'filterType' => GridView::FILTER_DATE_RANGE,
				'filterWidgetOptions' => [
			        'pluginOptions' => [
			        	'locale' => [ 
			        		'format' => 'YYYY-MM-DD',
			        		'separator'=>' to ',
			        	]
			        ],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Select Between Two Dates'],
			],
            [
                'label'=>'Full Name',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model)
                { 
                    return Html::a($model->address->name,['address' ,'id'=>$model->Delivery_ID],['data-toggle'=>"modal",'data-target'=>"#addressDetail",'data-title'=>"Address Detail",]);;
                },
            ],
			[
				'attribute' => 'Orders_TotalPrice',
				'format' => 'raw',
				'vAlign' => 'middle',
    			'hAlign' => 'center', 
				'mergeHeader'=>true,
				'value' => function($model)
                {
                    return Html::a(number_format($model->Orders_TotalPrice, 2, '.', ''),['price' ,'id'=>$model->Delivery_ID],['data-toggle'=>"modal",'data-target'=>"#orderDetail",'data-title'=>"Price Detail",]);
                },
              
			],
                 [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{resit}',
                'header' => "View Resit",
                'buttons' => [
                    'resit' => function($url,$model)
                    {   
                        return Html::a('Invoice Detail',['invoice-pdf','did'=>$model->Delivery_ID], ['target'=>'_blank' ,'class'=>'raised-btn main-btn']); 
                
                    }
                ],
            ],
			[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{speed}',
                'header' => "View Order Time",
                'buttons' => [
                    'speed' => function($url,$model)
                    {
                    	return Html::a("View Order Time",['ordertime' ,'id'=>$model->Delivery_ID],['data-toggle'=>"modal",'data-target'=>"#orderSpeed",'data-title'=>"Price Detail",]);
                
                    }
                ],
            ],
			[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}',
                'header' => "View Order Item",
                'buttons' => [
                    'view' => function($url , $model)
                    {
                        $url =  Url::to(['item' ,'id'=>$model->Delivery_ID]);

                        return Html::a('View Order Item' , $url , ['class' => 'text-underline','title' => 'Order Item'])   ;
                    },
                ],
            ],
        ],
    ]);
?> 
