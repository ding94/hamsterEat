<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\grid\ActionColumn;

    $this->title =  'Auth Permission';
    $this->params['breadcrumbs'][] = $this->title;


    echo GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'data',
                'value'=>function($model)use($list)
                { 
                    return $list[unserialize($model->data)];
                },
                'group'=>true, 
                'filter' => $list,
                'filterType'=>GridView::FILTER_SELECT2,
                'filterInputOptions'=>['placeholder'=>'Any List'],
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],

            [
                'attribute' => 'name',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Name',
                ],
            ],
            [
                'attribute' => 'description',
                'filterInputOptions' => [
                    'class'       => 'form-control',
                    'placeholder' => 'Search Description',
                ],
            ],
            'updated_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>

