<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;

$this->title = "Notification Setting";
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $model,
    'filterModel'=>$searchModel,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
    ],
    'columns'=>[
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
    			return $model->t->description;
    		},
    		'group'=>'true',
    	],
    	[
    		'attribute'=>'status',
    		'filter' => $array['status'],
			'filterType' => GridView::FILTER_SELECT2,
			'filterWidgetOptions' => [
			    'pluginOptions' => ['allowClear' => true],
			],
			'filterInputOptions' => ['placeholder' => 'Any Status'],
    		'value'=>function($model)
    		{
    			return $model->s->type;
    		},
    		'group'=>'true',
    	],
    	[
    		'attribute'=>'description',
    		'mergeHeader'=>true,
    	],
    	[
    		'attribute'=>'notificationType',
    		'filter' => $array['notic'],
			'filterType' => GridView::FILTER_SELECT2,
			'filterWidgetOptions' => [
			    'pluginOptions' => ['allowClear' => true],
			],
			'filterInputOptions' => ['placeholder' => 'Any Notification'],
    		'value'=>function($model)
    		{
    			return $model->settingType->description;
    		},
    	],
    	[
    		'attribute'=>'enable',
    		'filter' => $array['enable'],
			'filterType' => GridView::FILTER_SELECT2,
			'filterWidgetOptions' => [
			    'pluginOptions' => ['allowClear' => true],
			],
			'filterInputOptions' => ['placeholder' => 'Any Type'],
			'value'=>function($model)use($array)
			{
				return $array['enable'][$model->enable];
			},
    	],
    	[
    		'class' => 'kartik\grid\ActionColumn',
    		'template' => '{update}',
    	],
    ],
]);
?>
