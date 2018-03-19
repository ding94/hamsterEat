<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = $month.' Restaurant Ranking By Food Sold Per Month';
?>

<div class="site-index">
	<?php $form = ActiveForm::begin(['method' => 'get','action'=>['/restaurant/restaurant/restaurant-ranking-per-month']]); ?>
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
        'labels' => $data['rname'],
        'datasets' => [
            [
                'label' => "Food Sold",
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