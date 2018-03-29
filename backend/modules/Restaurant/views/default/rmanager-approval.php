<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use yii\bootstrap\Modal;

  $this->title = 'Restuarant Detail';
  $this->params['breadcrumbs'][] = $this->title;
  
?>

  <?= GridView::widget([
        'dataProvider' => $model,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'uid',
                'headerOptions' => ['width' => "15px"],
                'filterInputOptions' => ['placeholder' => 'Search ID'],
            ],
            [
                'attribute' => 'User_Username',
                'filterInputOptions' => ['placeholder' => 'Search User'],
            ],
            [
                'attribute' =>'Rmanager_NRIC',
                'filterInputOptions' => ['placeholder' => 'Search User'],
            ], 
            [
                'attribute' => 'Rmanager_DateTimeApplied',
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

            [
                'attribute'=>'Rmanager_Approval',
                'value' => function($model){
                     return $model['Rmanager_Approval'] == 0 ?  'Pending' :  'Approved';
                },
                'filter' => array( "1"=>"Approved","0"=>"Pending"),
            ],  
            [

                'format' => 'raw',
                'value' => function($model,$url)
                {

                    if($model['Rmanager_Approval'] == 0)
                    {
                        $url =Url::to(['/restaurant/default/rmanager-operate','id' =>$model->uid,'case'=>1]);
                    }
                    else
                    {
                        $url = Url::to(['/restaurant/default/rmanager-operate','id' =>$model->uid,'case'=>2]);
                    }

                    return $model['Rmanager_Approval'] == 0 ?  Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'Activate']) :  Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'Deactivate']);
                },
            ],
        ]
    ]); ?>