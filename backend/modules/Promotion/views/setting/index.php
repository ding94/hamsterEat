<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'All Promotion List';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'containerOptions' => ['style' => 'overflow: auto'], 
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
    'hover' => true,
    'toolbar' =>  [
        ['content' => 
            Html::a('<i class="glyphicon glyphicon-plus"></i>',['generate'], ['type' => 'button', 'title' => 'Add Promotion', 'class' => 'btn btn-success']) . ' '.
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' =>'Reset Grid'])
        ],
        
        '{toggleData}',
    ],
    // set export properties
    'export' => [
        'fontAwesome' => true
    ],
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => $this->title,
    ],
    'columns'=>[
        'id',
        [
            'attribute'=>'type_promotion',
            'value'=>'typePromotion.description',
            'filter' =>  $array['type'],
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Any Promotion Type'],
        ],
        [
            'attribute'=>'type_discount',
            'value'=>function($model)use($array)
            {
                return $array['discount'][$model->type_discount];
            },
            'filter' =>  $array['discount'],
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Any Discount Type'],
        ],
        [
            'attribute'=>'discount',
            'vAlign' => 'middle',
            'hAlign' => 'center', 
            'width'=>'7%', 
        ],
        [
            'attribute'=>'first',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'value' => 'start_date',
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'locale' => [ 
                        'format' => 'YYYY-MM-DD',
                        'separator'=>' to ',
                    ]
                ],
            ],
            'width'=>'7%',
            'filterInputOptions' => ['placeholder' => 'Set Between Two Dates'],
        ],
        [
            'attribute'=>'last',
            'vAlign' => 'middle',
            'hAlign' => 'center', 
            'filterType' => GridView::FILTER_DATE_RANGE,
            'value' => 'end_date',
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'locale' => [ 
                        'format' => 'YYYY-MM-DD',
                        'separator'=>' to ',
                    ]
                ],
            ],
            'width'=>'7%',
            'filterInputOptions' => ['placeholder' => 'Set Between Two Dates'],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'template'=>'{view}{update}{delete} ',

                'urlCreator' => function($action,$url,$model)
                {
                    if ($action === 'update'){
                       
                        return Url::to(['generate','id'=>$model]);
                    }
                  
                },
              
        ],
    ],
]);
?>