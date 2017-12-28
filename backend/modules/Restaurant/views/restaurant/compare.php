<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\db\ActiveRecord;
use dosamigos\chartjs\ChartJs;

    $this->title = "Restaurant ".$id." Earning Compare With Restaurant ".$oid." Earning";
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restuarant Detail '), 'url' => ['default/index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-9">
            <?= Html::a('Back',Yii::$app->request->referrer, ['class' => 'btn-block ']) ?>
        </div>
    </div>
    <div class="row">
        <?php foreach($totalProfit as $date=>$data):?>
        <div class="col-md-6">
        <h3>Restaurant <?php echo $id ?> Earning</h3>
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
    <?php foreach($totalProfitOther as $date=>$data):?>
        <div class="col-md-6">
        <h3>Restaurant <?php echo $oid ?> Earning</h3>
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
    </div>
   
