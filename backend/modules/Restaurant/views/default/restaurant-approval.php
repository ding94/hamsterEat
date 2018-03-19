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
                'attribute' => 'Restaurant_ID',
                'headerOptions' => ['width' => "15px"],
            ],
            [
                'attribute' => 'Restaurant_Name',
            ],
            'Restaurant_DateTimeCreated:datetime',
            [
                'value' => function($model,$url){
                     return $model['approval'] == 0 ?  'Rejected' :  'Approved';
                }
            ],
            [
                'format' => 'raw',
                'value' => function($model,$url)
                {
                    if($model['approval'] == 0)
                    {
                        $url =Url::to(['/restaurant/default/restaurant-operate','id' =>$model->Restaurant_ID,'case'=>1]);
                    }
                    else
                    {
                        $url = Url::to(['/restaurant/default/restaurant-operate','id' =>$model->Restaurant_ID,'case'=>2]);
                    }
                
                    return $model['approval'] == 0 ?  Html::a('Approve Restaurant' , $url , ['title' => 'Activate']) :  Html::a('Reject Restaurant' , $url , ['title' => 'Deactivate']);
                },
                'filter' =>  array( 0=>"Rejected",1=>"Approved"),
            ],
        ]
    ]); ?>