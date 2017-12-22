<?php

/* @var $this yii\web\View */
use yii\web\Request;
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Total Order And Delivery';
$fid = Yii::$app->request->get('fid');
?>
<div class="site-index">
    <?php $form = ActiveForm::begin(['method' => 'get','action'=>['rating/food-rating-stats']]); ?>
    <input type="hidden" name="fid" value="<?php echo $fid; ?>">
    <label class="control-label">Choose Selection</label>
    <div class="row">
        <div class="col-md-6">
            <?php
                echo DatePicker::widget([
                        'name' => 'year',
                        'value' => $year,
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy'
                    ]
                ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php echo Select2::widget([
                'name' => 'type',
                'hideSearch' => true,
                'value' => $type,
                'data' => $arrayType,
                'options' => [
                    'multiple' => false,
                ],
            ]);?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('Filter', ['class' => 'btn-block ']) ?>
        </div>
     </div>
<?php ActiveForm::end(); ?> 
    <?= ChartJs::widget([
    'type' => $arrayType[$type],
    'options' => [
    ],
    'data' => [
        'labels' => $data['months'],
        'datasets' => [
            [
                'label' => "Rating",
                'backgroundColor' => "#f45b69",
                'borderColor' => "#f67884",
                'pointBackgroundColor' => "rgba(255,99,132,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                'data' => $data['allrating']
            ],
        ]
    ]
]);
?>
</div>
