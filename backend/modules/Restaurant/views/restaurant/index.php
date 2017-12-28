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
use kartik\export\ExportMenu;
use dosamigos\chartjs\ChartJs;
use iutbay\yii2fontawesome\FontAwesome as FA;

    $this->title = "Restaurant ".$id." Earning";
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restuarant Detail '), 'url' => ['default/index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['method' => 'get','action'=>['/restaurant/restaurant/profit','id'=>$id]]); ?>
    <label class="control-label">Search ...</label>
    <div class="row">
        <div class="col-md-9">
            <?php
                echo DatePicker::widget([
                        'name' => 'first',
                        'value' => $first,
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'startView'=>'year',
                            'minViewMode'=>'months',
                            'format' => 'yyyy-m'
                            ]
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('Search', ['class' => 'btn-block ']) ?>
        </div>
     </div>
<?php ActiveForm::end(); ?>
    <div class="row">
        <?php foreach($totalProfit as $date=>$data):?>
        <div class="col-md-6">
        <h4><?php echo $date; ?></h4>
        <?= ChartJs::widget([
    'type' => 'doughnut',
    'options' => [
    ],
    'data' => [
        'labels' => ['Cost','Selling Price'],
        'datasets' => [
            [
                'label' => ['Cost','Selling Price'],
                'backgroundColor' => ["#f45b69","#ffda00"],
                'data' => [$data['cost'],$data['sellPrice']]
            ],
        ]
    ]
]);
?>
            <table class="table table-bordered">
                <tr>
                    <td>Total Cost</td>
                    <td><?php echo $data['cost']?></td>
                </tr>
                <tr>
                    <td>Total Sell Price</td>
                    <td><?php echo $data['sellPrice']?></td>
                </tr>
            </table>
        </div>
    <?php endforeach ;?>
    <?php $form = ActiveForm::begin(['method' => 'post']); ?>
        <div class="col-md-3">
            <?php
                echo $form->field($tempmodel, 'Restaurant_ID')->widget(Select2::classname(),[
                        'name' => 'compare',
                        'data' => $restaurantlist,
                        'options' => [
                            'placeholder' => 'Compare with ...',
                        ],
                ])->label('Compare with...');
            ?>
            <?= Html::submitButton('Compare',['class' => 'btn-block ']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
<?php  echo GridView::widget([
        'dataProvider'=>$model,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'striped'=>false,
        'hover'=>true,
        'showPageSummary' => true,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'panel'=>[
            'type'=>GridView::TYPE_SUCCESS,
          
        ],
         'exportConfig' =>[ExportMenu::EXCEL => false,
                        ExportMenu::PDF => false],
        //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'columns'=>[
            'oid',
            [
                'header'=>'Original',
                'hAlign'=>'right',
                'value' => 'original',
                
            ],
            [
                'header'=>'Quantity',
                'hAlign'=>'right',
                'value' => 'quantity',
                
            ],
            [
                'header'=>'Cost',
                'hAlign'=>'right',
                'value' => 'cost',
                'pageSummary' => true,
                'format'=>['decimal', 2],
            ],
            [
                'hAlign'=>'right',
                'class'=>'kartik\grid\FormulaColumn',
                'header'=>'Mark Up 30%',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'mergeHeader' => true,
                'value' => function ($model, $key, $index, $widget) { 
                    $p = compact('model', 'key', 'index');
                    return $widget->col(5, $p) - $widget->col(3, $p);
                },

                'format'=>['decimal', 2],     
            ],
            [
                'header'=>'Sell Price',
                'hAlign'=>'right',
                'value' => 'sellPrice',
                'pageSummary' => true,
                'format'=>['decimal', 2],
            ],
            'created_at:datetime'
        ],
        
    ]);
  
?>
   
