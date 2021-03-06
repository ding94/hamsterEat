<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Total Food Sold Per Month';
$fid = Yii::$app->request->get('fid');
?>
<div class="site-index">
<?php $form = ActiveForm::begin(['method' => 'get','action'=>['food/food-sold']]); ?>
    <input type="hidden" name="fid" value="<?php echo $fid; ?>">
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
            <?= Html::submitButton('Filter', ['class' => 'btn-block ']) ?>
        </div>
     </div>
<?php ActiveForm::end(); ?>
    <div class="row">
        <div class="col-md-3">
            <table class="table">
                <tbody>
                    <tr>
                        <td>Total Food Sold In This Period</td>
                        <td><?php echo $data['totalcount']; ?></td>    
                    </tr>
                </tbody>
            </table>
        </div>
    </div> 
<?= ChartJs::widget([
    'type' => 'bar',
    'options' => [
    ],
    'data' => [
        'labels' => $data['date'],
        'datasets' => [
            [
                'label' => "Food Sold",
                'backgroundColor' => "#f45b69",
                'borderColor' => "#f67884",
                'pointBackgroundColor' => "rgba(255,99,132,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                'data' => $data['count']
            ]
        ]
    ]
]);
?>
</div>
