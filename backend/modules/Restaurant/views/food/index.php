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
  $array = ['-1'=>'Deleted','0'=>'Close','1'=>'Open'];
  echo GridView::widget([
        'dataProvider'=>$model,
        'filterModel'=>$searchModel,
        //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax'=>false, // pjax is set to always true for this demo
        //'panel'=>['type'=>'primary', 'heading'=>'Rating List'],
        'columns'=>[
            [
                'attribute' => 'name',
                'value' => 'transName.translation',
            ],
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
                'format'=>'raw',
                'value' => function($model)use($array)
                {

                    if($model->foodStatus['Status'] == -1)
                    {
                        $url =Url::to(['food-recover','id' =>$model->Food_ID ]);
                        $name = "Recover";
                    }
                    else
                    {
                        if($model->foodStatus['Status'] == 0)
                        {
                            $status = 1;
                            $name = "Turn On";
                        }
                        else
                        {
                            $status = 0;
                            $name = "Turn Off";
                        }
                        $url =Url::to(['food-control','id' =>$model->Food_ID ,'status' => $status]);
                       
                    }
                    $html = "<div class='row'><div class='col-xs-6'>";
                    $html .= $array[$model->foodStatus->Status];
                    $html .="</div><div class='col-xs-6'>";
                    $html .= 
                    $html .= "</div></div>";
                    return $array[$model->foodStatus->Status]." | ". Html::a($name,$url);;
                },
                 'filter' => $array,
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{detail}',
                'header' => 'Detail',
                'buttons'=>[
                    'detail' => function($url,$model)
                    {
                        return Html::a('view',['type/index','id'=>$model->Food_ID]);
                    } 
                ],
                'hAlign'=>'center', 
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{foodrating}',
                'header' => "Food Rating",
                'buttons' => [
                    'foodrating' => function($url , $model)
                    {
                        $url =  Url::to(['/rating/food-rating-stats' ,'fid'=>$model->Food_ID]);

                        return Html::a('View' , $url , ['class' => 'text-underline','title' => 'Food Rating'])   ;
                    },
                ],
                'hAlign'=>'center', 
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{foodsold}',
                'header' => "Food Sold",
                'buttons' => [
                    'foodsold' => function($url , $model)
                    {
                        $url =  Url::to(['food/food-sold' ,'fid'=>$model->Food_ID]);

                        return Html::a('View' , $url , ['class' => 'text-underline','title' => 'Food Sold'])   ;
                    },
                ],
                'hAlign'=>'center', 
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}',
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'panel'=>[
            'type'=>GridView::TYPE_SUCCESS,
          
        ],
        
    ]);
  
?>
   
