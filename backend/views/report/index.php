<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use yii\bootstrap\Modal;

	$this->title = 'Report';
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
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
                	[   
	                  	'attribute' => 'Report_ID',
	                  	'filterInputOptions' => ['placeholder' => 'Search ID', 'class'=>'form-control'],
	                  	'width' =>'36px',
                	],
    	   			[	
    	   				'label' =>'Reported Restaurant',
                  		'attribute'=>'Report_PersonReported',
                  		'width' =>'20%',
              	    ],
                   	[
	                  	'label' => 'Report By',
	                   	'attribute' => 'uid',
	                   	'value' => function($model)
		                {
		                	return Html::a($model->userid->username,['detail' ,'id'=>$model->userid->id],['data-toggle'=>"modal",'data-target'=>"#userDetail",'data-title'=>"User Detail",]);
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
    	          		'attribute'=>'Report_Category',
    	          		'filterType' => GridView::FILTER_SELECT2,
						'filter' => $arrayData['status'],
						'filterWidgetOptions' => [
					        'pluginOptions' => ['allowClear' => true],
					    ],
					    'filterInputOptions' => ['placeholder' => 'Any Status','class'=>'form-control'],
					    'value'=>function($model)use($arrayData){
					  
					    	return $arrayData['status'][$model->Report_Category];
					    }
     				],     		
			        [ 
    	          		'attribute'=>'Report_Reason',
     				],
    	          	[
						'attribute' => 'Report_DateTime',
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
						'filterInputOptions' => ['placeholder' => 'Select Between Two Dates', 'class'=>'form-control'],
					],
        ],
    ])?>
	