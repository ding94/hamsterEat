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
            ],
            [
                'attribute' => 'User_Username',
            ],
            'Rmanager_NRIC',
            'Rmanager_DateTimeApplied:datetime',
            [
                'value' => function($model,$url){
                     return $model['Rmanager_Approval'] == 0 ?  'Pending' :  'Approved';
                }
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