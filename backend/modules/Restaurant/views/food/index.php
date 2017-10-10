<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = $searchModel->restaurant;
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
            ['class'=>'kartik\grid\SerialColumn'],
            [
                'class'=>'kartik\grid\ExpandRowColumn',
                'width'=>'50px',
                'value'=>function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail'=>function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('detail', ['model'=>$model->foodSelection]);
                },
                'headerOptions'=>['class'=>'kartik-sheet-style'] ,
                'expandOneOnly'=>true,
            ],
            'Name',
            'BeforeMarkedUp',
            'Price',
            'Description',
            [
                'attribute' => 'foodType',
                'value' => function($model)
                {
                    foreach($model->foodType as $type)
                    {
                        $data[] = $type->Type_Desc;;
                    }
                    return implode(",",$data);
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filter' => $typeList,
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>'Any Type'],
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model)
                {
                    if($model->foodStatus['Status'] == 0)
                    {
                        $url =Url::to(['food/food-control','id' =>$model->Food_ID ,'status' => 1]);
                    }
                    else
                    {
                        $url =Url::to(['food/food-control','id' =>$model->Food_ID ,'status' => 0]);
                    }
                    return $model->foodStatus['Status'] == 0 ?  Html::a(FA::icon('toggle-off lg') , $url , ['title' => 'ON']) :  Html::a(FA::icon('toggle-on lg') , $url , ['title' => 'OFF']);
                },
                'filter' =>  array( 0=>"Close",1=>"Open"),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'panel'=>[
            'type'=>GridView::TYPE_SUCCESS,
          
        ],
        'persistResize'=>false,
    ]);
  
?>
   
