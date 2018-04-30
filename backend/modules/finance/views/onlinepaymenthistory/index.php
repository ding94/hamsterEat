<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use common\models\PaymentGateWay\PaymentBill;
use iutbay\yii2fontawesome\FontAwesome as FA;

$this->title = 'Online Payment History';
$this->params['breadcrumbs'][] = $this->title;
	 Modal::begin([
        'id' => 'PaymentDetail',
        'header' => '<h4 class="modal-title">...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
  
    Modal::end();
    $this->registerJs("
        $('#PaymentDetail').on('show.bs.modal', function (event) {
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
            })
    ");

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
        	
			 ['class' => 'yii\grid\ActionColumn' , 
             'template'=>'{active} ',
             'header' => "View",
             'headerOptions' => ['width' => '20px'],
             'contentOptions' => ['class' => 'text-center'],	
             'buttons' => [
                'active' => function($url , $model)
                {
                  
                    return  Html::a('<i class="fa fa-eye"></i>',['detail' ,'id'=>$model->bill_id], ['data-toggle'=>"modal",'data-target'=>"#PaymentDetail",'data-title'=>"Payment Detail",]);
                },
              ]
            ],
            [	
            	'label'=> 'Bill ID',
            	'attribute' =>'bill_id',
            	'filterInputOptions' => [
	                'class' => 'form-control',
	                'placeholder' => 'Bill ID'
            	]
        	],
			[
				'label'=> 'Collect_id',
				'attribute'=> 'collect_id',
				'filterInputOptions' => [
					'class' => 'form-control',
					'placeholder' => 'Collect ID'
				]
			],
         	
			[
				'label'=>'Payment ID',
				'attribute'=>'pid',
				'filterInputOptions'=>[
					'class'=>'form-control',
					'placeholder'=>'Payment ID',
				],
			],
			[	
				'label'=>'Status',
				'attribute'=>'status',
				'width' => '130px',
				'value'=>function($model,$url){
					if($model->status==1):
						return 'Paid';
					elseif($model->status==2):
						return 'Hidden';
					else:
						return 'Delete';
					endif;
				},
				'filter' => array(0=>"Delete",1=>"Paid",2=>"Hidden"),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
	                'pluginOptions' => ['allowClear' => true],
	            ],
	            'filterInputOptions' => ['placeholder' => 'Any Type'],
			],
			[
			 'attribute' => 'created_at',
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
            'updated_at:datetime',
        ],
    ]);
?> 
