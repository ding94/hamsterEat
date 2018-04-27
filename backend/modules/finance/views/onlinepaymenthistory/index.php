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
        	
			 ['class' => 'yii\grid\ActionColumn' , 
             'template'=>'{active} ',
             'header' => "View",
             'buttons' => [
                'active' => function($url , $model)
                {
                  
                    return  Html::a('<i class="fa fa-eye"></i>',['detail' ,'id'=>$model->bill_id], ['data-toggle'=>"modal",'data-target'=>"#userDetail",'data-title'=>"Payment Detail",]);
                },
              ]
            ],
			'bill_id',
         	'collect_id',
			'pid',
			'status',
			'created_at:datetime',
            'updated_at:datetime',
        ],
    ]);
?> 
