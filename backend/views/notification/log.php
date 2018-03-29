<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;

$this->title = "Sms Log";
$this->params['breadcrumbs'][] = $this->title;
	echo GridView::widget([
	    'dataProvider' => $model,
	    'filterModel'=>$searchModel,
	    'pjax' => true,
	    'panel' => [
	        'type' => GridView::TYPE_PRIMARY,
	        'heading' => "SMS LOG",
	    ],
	    'toolbar' =>  [
	        ['content' => 
	            
	            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['sms-log'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' =>  'Reset Grid'])
		        ],
		        '{export}',
		        '{toggleData}',
	    ],
	    'columns'=>[
	    	[
	    		'attribute' =>'id',
	    		'mergeHeader'=>true,
	    	],
	    	[
	    		'attribute'=>'type',
	    		'filter' => $array['type'],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
				    'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Any Type'],
	    		'value'=>function($model)
	    		{
	    			return $model->noticType->description;
	    		}
	    	],
	    	[
	    		'attribute' =>'result',
	    		/*'filter' => $array['result'],
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions' => [
				    'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Any Result'],*/
	    	],
	    	'number',
	    	[
	    		'attribute' => 'content',
	    		'mergeHeader'=>true,
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
	    
	    ],
	]);
?>