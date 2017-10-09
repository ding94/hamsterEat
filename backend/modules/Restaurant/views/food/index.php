<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = 'Food Detail';
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restuarant Detail '), 'url' => ['default/index']];
  $this->params['breadcrumbs'][] = $this->title;

  echo GridView::widget([
        'dataProvider'=>$model,
        'filterModel'=>$searchModel,
        //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax'=>true, // pjax is set to always true for this demo
        //'panel'=>['type'=>'primary', 'heading'=>'Rating List'],
        'columns'=>[
            [
                'attribute' => 'foodName',
                'value' => function($model)
                {
                    return $model->food->Name;
                },
                'group' => true,
            ],
            [
                'attribute' => 'foodPrice',
                'value' => function($model)
                {
                    return $model->food->Price;
                },
                'group' => true,
            ],
            [
                'attribute' => 'type',
                'value' => function($model)
                {
                    return $model->foodselectiontype->TypeName;
                },
                'group' => true,
            ],
            'Name',
            [
                'attribute' => 'Status',
                'format' => 'raw',
                'value' => function($model)
                {
                     if($model->Status == 0)
                    {
                        $url =Url::to(['food/active','name' =>$model->ID]);
                    }
                    else
                    {
                        $url = Url::to(['food/deactive','name' =>$model->ID]);
                    }
                
                    return $model->Status == 0 ?  Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'ON']) :  Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'OFF']);
                },
                'filter' =>  array( 0=>"Close",1=>"Open"),
            ],
            

        ],
        'panel'=>[
            'type'=>GridView::TYPE_SUCCESS,
          
        ],
        'persistResize'=>false,
    ]);
  
?>
   
