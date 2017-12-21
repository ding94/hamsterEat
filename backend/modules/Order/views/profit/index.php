<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = "Order Profit";
  $this->params['breadcrumbs'][] = $this->title;

  echo GridView::widget([
        'dataProvider'=>$model,
        'filterModel'=>$searchModel,    
        'pjax'=>true,
        'striped'=>false,
        'hover'=>true,
        //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'columns'=>[
            [
                'header' => 'did',
                'value'=>function ($model, $key, $index, $widget) { 
                    return "Delivery ID : ".$model->did;
                },
                'group'=>true, 
                'groupedRow'=>true,  
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                'groupFooter' => function ($model,$key,$index,$widget)
                {
                    return[
                        'content'=>[             // content to show in each summary cell
                            1=>"Total<br>Discount<br>Final Total",
                            4=>GridView::F_SUM,
                            5=>GridView::F_SUM,
                            6=>$model->finalSum."<br>-".$model->discount."<br>".$model->TotalSum,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                            4=>['format'=>'number', 'decimals'=>2],
                            5=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            6=>['style'=>'text-align:right'],
                        ],
                    'options'=>['class'=>'success','style'=>'font-weight:bold;']
                    ];
                }
            ],
            'oid',
            [
                'header' => 'Single Price',
                'value' => 'original',
                'mergeHeader'=>true,
            ],
            [
                'header' => 'Quantity',
                'value' => 'quantity',
                'mergeHeader'=>true,
            ],
            [
                'header' => 'Cost',
                'value' => 'cost',
                'mergeHeader'=>true,
            ],
            [
                'class'=>'kartik\grid\FormulaColumn',
                'header'=>'Mark Up 30%',
                'value'=>function ($model, $key, $index, $widget) { 
                    $p = compact('model', 'key', 'index');
                    return $widget->col(6, $p) -$widget->col(4,$p);
                },
                'mergeHeader'=>true,
                'format'=>['decimal', 2],
                
            ],
            [
                'hAlign'=>'right',
                'header' => 'Selling Price',
                'value' => 'sellPrice',
                'mergeHeader'=>true,
            ],
        ],
        
    ]);
  
?>
   
