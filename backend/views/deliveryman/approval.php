<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = 'DeliveryMan Approval';
  $this->params['breadcrumbs'][] = $this->title;
  
echo GridView::widget([
    'dataProvider' => $model,
    'filterModel' => $searchModel,
    'pjax' => true,
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => $this->title,
    ],
    'toolbar' =>  [
        ['content' => 
                
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['approval'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' =>  'Reset Grid'])
            ],
            '{export}',
            '{toggleData}',
    ],
    'columns'=>[
        [
            'attribute' => 'username',
            'value' => 'user.username',
        ],
        [
            'attribute' => 'DeliveryMan_VehicleType',
            'mergeHeader'=>'true',
        ],
        [
            'attribute'=>'status',
            'value' => function($model,$url){
                return $model->DeliveryMan_Approval == 0 ?  'Rejected' :  'Approved';
            },
            'filter' =>  array( 0=>"Rejected",1=>"Approved"),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Any Result'],
        ],
        [
            'attribute' => 'timeApplied',
            'format' => 'datetime',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'value' => 'DeliveryMan_DateTimeApplied',
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'locale' => [ 
                        'format' => 'YYYY-MM-DD',
                        'separator'=>' to ',
                    ]
                ],
            ],
            'filterInputOptions' => ['placeholder' => 'Set Between Two Dates'],
        ],
        [
            'attribute' => 'timeApprove',
            'format' => 'datetime',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'value' => 'DeliveryMan_DateTimeApproved',
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'locale' => [ 
                        'format' => 'YYYY-MM-DD',
                        'separator'=>' to ',
                    ]
                ],
            ],
            'filterInputOptions' => ['placeholder' => 'Set Between Two Dates'],

        ],
        ['class' => 'yii\grid\ActionColumn' , 
            'template'=>' {active} ',
            'header' => "Action",
            'buttons' => [
                'active' => function($url , $model)
                {
                    if($model->DeliveryMan_Approval == 0)
                    {
                         $url = Url::to(['active' ,'id'=>$model->User_id]);
                    }
                    else
                    {
                        $url = Url::to(['deactive' ,'id'=>$model->User_id]) ;
                    }
                   
                    return  $model->DeliveryMan_Approval == 1  ? Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']) : Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']);
                },
              ]
            ],
    ],
]); 
?>