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
                'filterInputOptions' => ['placeholder' => 'Search ID', 'class'=>'form-control'],
            ],
            [
                'attribute' => 'Restaurant_Name',
                'filterInputOptions' => ['placeholder' => 'Search Restaurant Name', 'class'=>'form-control'],
            ],

            [
                'attribute' =>'Restaurant_DateTimeCreated',
                'value' => 'Restaurant_DateTimeCreated',
                'filter' => \yii\jui\DatePicker::widget(['model'=>$searchModel, 'attribute'=>'Restaurant_DateTimeCreated', 'dateFormat' => 'yyyy-MM-dd',]),
                'format' => 'datetime',
               
            ],
            [   
                'attribute' =>'Restaurant_Status',
                'value' => function($model,$url){
                     return $model['approval'] == 0 ?  'Rejected' :  'Approved';
                },
                'filter' => array( "1"=>"Approved","0"=>"Rejected"),
                'filterInputOptions' => ['prompt' => 'Approved and Rejected', 'class'=>'form-control'],
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