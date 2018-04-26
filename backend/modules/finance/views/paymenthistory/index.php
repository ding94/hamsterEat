<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\grid\ActionColumn;

$this->title = 'Payment History';
$this->params['breadcrumbs'][] = $this->title;
	 Modal::begin([
        'id' => 'userDetail',
        'header' => '<h4 class="modal-title">...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
  
    Modal::end();
    $this->registerJs("
        $('#userDetail').on('show.bs.modal', function (event) {
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
        	
			[    
                'label' => 'ID',
				'attribute' => 'id',
				'width' => '100px',	
				'filterInputOptions' => [
	                'class' => 'form-control',
	                'placeholder' => 'Pay ID'
            	]
			],
			[    
                'label' => 'User ID',
				'attribute' => 'uid',
				'width' => '100px',	
				'filterInputOptions' => [
	                'class' => 'form-control',
	                'placeholder' => 'User ID'
            	]
			],
			[
                'label'=>'Username',
				'attribute' => 'username',
				'value' => function($model)
                {
                   
                     return Html::a($model->name->username,['detail' ,'id'=>$model->name->id],['data-toggle'=>"modal",'data-target'=>"#userDetail",'data-title'=>"User Detail",]);
                },
				'filter' => $arrayData['user'],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Any User'],
			    'format' => 'raw'
			],
			[
				'label' =>'Type',
	            'attribute'=>'paid_type',
	            'value' => function($model,$url){
	                return $model->paid_type == 1 ?  'Account Balance' :  'Fpx';
	            },
	            'filter' =>  array( 1=>"account balance",2=>"Fpx"),
	            'filterType' => GridView::FILTER_SELECT2,
	            'filterWidgetOptions' => [
	                'pluginOptions' => ['allowClear' => true],
	            ],
	            'filterInputOptions' => ['placeholder' => 'Any Type'],
					
			],
			[
               'label' => 'Paid Amount',
			   'attribute'=>'paid_amount',
			],
			
			'created_at:datetime',
            'updated_at:datetime',
			
        ],
    ]);
?> 
