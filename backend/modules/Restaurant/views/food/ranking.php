<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title ='Top 10 Food Ranking By Food Quantity Sold Per Month During '.$textmonth;
$path = Yii::$app->request->get('rid');
?>

<div class="site-index">
    <?php if($path == null){ 
	 $form = ActiveForm::begin(['method' => 'get','action'=>['/restaurant/food/food-ranking-per-month']]); 
        } else {
    $form = ActiveForm::begin(['method' => 'get','action'=>['/restaurant/food/food-ranking-per-restaurant-per-month','rid'=>$path]]); }?>
	    <label class="control-label">Choose Selection</label>
    <div class="row">
        <div class="col-md-6">
            <?php
                echo DatePicker::widget([
                        'name' => 'month',
                        'value' => $month,
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-m',
                            'minViewMode' => 1,
                    ]
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('Filter', ['class' => 'btn-block']) ?>
        </div>
     </div>
	<?php ActiveForm::end(); ?>
	<?= ChartJs::widget([
    'type' => 'horizontalBar',
    'options' => [
    ],
    'data' => [
        'labels' => $data['fname'],
        'datasets' => [
            [
                'label' => "Quantity Sold",
                'backgroundColor' => "#f45b69",
                'borderColor' => "#f67884",
                'pointBackgroundColor' => "rgba(255,99,132,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                'data' => $data['count'],
            ],
        ]
    ]
]);
?>
</div>