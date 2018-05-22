<?php

use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;

if($id == 0)
{
	$this->title = 'All Order Item List';
}
else
{
	$this->title = 'Order ID:'.$id. ' Item List';
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Delivery List '), 'url' => ['index']];
}

$this->params['breadcrumbs'][] = $this->title;
	
	Modal::begin([
        'id' => 'itemSpeed',
        'header' => '<h4 class="modal-title">...</h4>',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    Modal::end();
    $this->registerJs("
        $('#itemSpeed').on('show.bs.modal', function (event) {
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
        	 	'attribute'=>'Delivery_ID',
        	 	'value' => function($model)use($id)
        	 	{
        	 		return "Delivery ID : ". $model->Delivery_ID;
        	 	},
        	 	'group'=>true,  
            	'groupedRow'=>true,                  
            	'groupOddCssClass'=>'kv-grouped-row',  
            	'groupEvenCssClass'=>'kv-grouped-row', 
        	 ],
        	 [
        	 	'class'=>'kartik\grid\ExpandRowColumn',
        	 	'width'=>'50px',
        	 	'value'=>function ($model, $key, $index, $column) {
                    if(empty($model['order_selection']))
                    {
                        GridView::ROW_NONE;
                        return "";
                    }
                    else
                    {
                        return GridView::ROW_COLLAPSED;
                    }
                  
                },
                 'detail'=>function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('_detail', ['model'=>$model->order_selection]);
                
                },
                'headerOptions'=>['class'=>'kartik-sheet-style'] ,
                'expandOneOnly'=>false,
        	],
        	[   
                'attribute' => 'Order_ID',
                'filterInputOptions' => ['placeholder' =>'Search ID','class' =>'form-control'],
            ],
        	'OrderItem_Quantity',
            [
                'label' => 'Food Name',
                'attribute' => 'foodName',
                'value' => 'food.originName',
                'filterInputOptions' =>['placeholder'=> 'Search Food ', 'class' =>'form-control'],
            ],
        	'OrderItem_LineTotal',

        	[
        		'attribute'=>'OrderItem_Status',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => $allstatus,
				'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
			    ],
			    'filterInputOptions' => ['placeholder' => 'Any Status'],
        		
        		'value' => function($model)use($allstatus)
        		{
        			return $allstatus[$model->OrderItem_Status];
        		}
        	],
        
        	'OrderItem_Remark',
        	[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{order}',
                'header' => "View Order",
                'buttons' => [
                    'order' => function($url , $model)
                    {
                        $url =  Url::to(['index' ,'did'=>$model->Delivery_ID]);
                        return  Html::a("View Order",$url);
                    },
                ],
            ],
        	[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{speed}',
                'header' => "View Order Item Speed",
                'buttons' => [
                    'speed' => function($url , $model)
                    {
                        $url =  Url::to(['itemtime' ,'id'=>$model->Order_ID]);
                        return  Html::a("View All Time",$url,['data-toggle'=>"modal",'data-target'=>"#itemSpeed",'data-title'=>"Order Item Time",]);  
                    },
                ],
            ],
        ]
     ]);

?>