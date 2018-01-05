<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;

  $this->title = "Order Profit";
  $this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(['method' => 'get','action'=>['/order/profit/index']]); ?>
    <label class="control-label">Search ...</label>
    <div class="row">
        <div class="col-md-6">
            <?php
                echo DatePicker::widget([
                        'name' => 'first',
                        'value' => $first,
                        'type' => DatePicker::TYPE_RANGE,
                        'name2' => 'last',
                        'value2' => $last,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-m-d'
                    ]
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=Html::input('text','id','',['class'=>'form-control', 'placeholder' => "Enter Delivery ID"])?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('Search', ['class' => 'btn-block ']) ?>
        </div>
     </div>
<?php ActiveForm::end(); ?> 
<?php   echo GridView::widget([
        'dataProvider'=>$model,
        'pjax'=>true,
        'striped'=>false,
        'hover'=>true,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
         'panel'=>[
            'type'=>'success',
            'layout'=>'{export} {toggleData}',
        ],
        //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'columns'=>[
            [
               'format' => 'raw',

                'value'=>function ($model, $key, $index, $widget) { 
                    return "<div class='col-md-8'>Delivery ID : ".$model->did ."</div><div class='col-md-2 pull-right'>Time : ".$model->time."</div>";
                },

                'group'=>true, 
                'groupedRow'=>true,  
                'groupOddCssClass'=>'kv-grouped-row',  // configure odd group cell css class
                'groupEvenCssClass'=>'kv-grouped-row', // configure even group cell css class
                'groupFooter' => function ($model,$key,$index,$widget)
                {
                    return[
                        'content'=>[             // content to show in each summary cell
                            1=>"Total<br>Delivery Charge<br>Discount<br>Final Total",
                            4=>GridView::F_SUM,
                            5=>GridView::F_SUM,
                            6=>$model->finalSum."<br>".$model->delivery."<br>-".$model->discount."<br>".$model->TotalSum,
                        ],
                        'contentFormats'=>[      // content reformatting for each summary cell
                            4=>['format'=>'number', 'decimals'=>2],
                            5=>['format'=>'number', 'decimals'=>2],
                        ],
                        'contentOptions'=>[      // content html attributes for each summary cell
                            4=>['style'=>'text-align:right'],
                            5=>['style'=>'text-align:right'],
                            6=>['style'=>'text-align:right'],
                        ],
                    'options'=>['class'=>'success','style'=>'font-weight:bold;']
                    ];
                }
            ],
            [    
                'header' => 'Order ID',
                'value' => 'oid',
                'mergeHeader'=>true,
            ],
          
            [
                'hAlign'=>'right',
                'header' => 'Single Price',
                'value' => 'original',
                'mergeHeader'=>true,
            ],
            [
                'hAlign'=>'right',
                'header' => 'Quantity',
                'value' => 'quantity',
                'mergeHeader'=>true,
            ],
            [
                'hAlign'=>'right',
                'header' => 'Cost',
                'value' => 'cost',
                'mergeHeader'=>true,
            ],
            [
                'hAlign'=>'right',
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
   
