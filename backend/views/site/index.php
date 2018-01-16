<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Total Order And Delivery';
?>
<div class="site-index">
<?php $form = ActiveForm::begin(['method' => 'get','action'=>['site/index']]); ?>
    <label class="control-label">Choose Selection</label>
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
<div class="row">
    <div class="col-md-3">
        <table class="table">
            <tbody>
                <tr>
                    <td>Order Price</td>
                    <td class="pull-right"><?= $data['final']['orderFinalPrice']  ?></td>
                </tr>
                <tr>
                    <td>Delivery Charge</td>
                    <td class="pull-right"><?= $data['final']['totalDeliveryCharge']?></td>
                </tr>
                <tr>
                    <td>Early Discount</td>
                    <td class="pull-right">-<?= $data['final']['earlyDiscount']?></td>
                </tr>
                <tr>
                    <td>Voucher Discount</td>
                    <td class="pull-right">-<?= $data['final']['voucherDiscount']?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="pull-right"><?= $data['final']['totalDelivery']?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= ChartJs::widget([
    'type' => $arrayType[$type],
    'options' => [
    ],
    'data' => [
        'labels' => $data['date'],
        'datasets' => [
            [
                'label' => "Orders",
                'backgroundColor' => "#f45b69",
                'borderColor' => "#f67884",
                'pointBackgroundColor' => "rgba(255,99,132,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                'data' => $data['countOrder']
            ],
            [
                'label' => "Delivery",
                'backgroundColor' => "#d5573b",
                'borderColor' => "#dc755e",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => $data['countDelivery']
        ],
        ]
    ]
]);
?>
</div>
