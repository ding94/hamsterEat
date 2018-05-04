<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;

$this->title = 'All Order Status';
$this->params['breadcrumbs'][] = $this->title;
	
	/* Modal::begin([
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

   	*/

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
                'attribute'=>'Delivery_ID',
                'width' => '120px',
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Delivery ID'
                ]
            ],
			[
        		'label'=>'Status',
                'attribute'=>'Orders_Status',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => $allstatus,
				'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Any Status'],
        		
        		'value' => function($model)use($allstatus)
        		{
        			return $allstatus[$model->Orders_Status];
        		}
        	],
            [
                'label' =>'Payment Method',
                'attribute'=>'Orders_PaymentMethod',
                'filter' =>  array( 'Unpaid'=>"Unpaid",'User Balance'=>"User Balance",'Online Banking'=>"Online Banking",'Cash on Delivery'=>"Cash on Delivery"),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Any Type'],
                    
            ],    
        	[
                'attribute' => 'Orders_DateTimeMade',
                'format' => 'datetime',
                'width' => '320px',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'locale' => [ 
                            'format' => 'YYYY-MM-DD',
                            'separator'=>' to ',
                        ]
                    ],
                ],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Select Between Two Dates'],
            ],
	        
            [	
	        	'class' => 'yii\grid\ActionColumn' , 
	            'template'=>'{update} ',
	            'header' => "Update",
				'buttons' => [
				'update' => function ($url, $model) {  
	                    return Html::a(FA::icon('pencil lg'), $url, [
	                        'title' =>'Update',
	                    ]);                                
               		}
           		]
			],
			
			
        ],
    ]);
?> 

