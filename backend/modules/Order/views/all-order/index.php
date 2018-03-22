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
      Modal::begin([
        'id' => 'addressDetail',
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
        'pjax'=>true,
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
                'label'=>'Payment Method',
				'attribute' => 'Orders_PaymentMethod',
				'filter' => ['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Any Method'],
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
                    $html = "<div class='col-md-6'>";
                    $html .= $model->address->name;
                    $html .="</div><div class='col-md-6'>";
                    $html .=  Html::a("View Address Detail",['address' ,'id'=>$model->Delivery_ID],['data-toggle'=>"modal",'data-target'=>"#addressDetail",'data-title'=>"Address Detail",]);
                    $html .= "</div>";
                    return $html;
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
                	$html = "<div class='col-md-6'>";
                	$html .= number_format($model->Orders_TotalPrice, 2, '.', '');
                	$html .="</div><div class='col-md-6'>";
                	$html .=  Html::a("View Price Detail",['price' ,'id'=>$model->Delivery_ID],['data-toggle'=>"modal",'data-target'=>"#orderDetail",'data-title'=>"Price Detail",]);
                	$html .= "</div>";
                    return $html;
                },
              
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