<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;

	$this->title = 'Rating List';
	$this->params['breadcrumbs'][] = $this->title;



    echo GridView::widget([
       	'dataProvider'=>$model,
     	'filterModel'=>$searchModel,
       	'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
	    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
	    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
	    'pjax'=>true, // pjax is set to always true for this demo
        'panel'=>['type'=>'primary', 'heading'=>'Rating List'],
	    'columns'=>[
	       	['class'=>'kartik\grid\SerialColumn'],
	       	[
				'attribute' => 'Delivery_ID',
			    'width'=>'40px',
			],
	       
	       	[
			    'class'=>'kartik\grid\ExpandRowColumn',
			    'width'=>'50px',
			    'value'=>function ($model, $key, $index, $column) {
			        return GridView::ROW_COLLAPSED;
			    },
			    'detail'=>function ($model, $key, $index, $column) {
			        return Yii::$app->controller->renderPartial('expand-service-details', ['model'=>$model]);
			    },
			    'headerOptions'=>['class'=>'kartik-sheet-style'] ,
			    'expandOneOnly'=>true,
			],
	       	'User_Username',
	    ],
	    'panel'=>[
	        'type'=>GridView::TYPE_SUCCESS,
	      
	    ],
	    'persistResize'=>false,
    ]);


?>