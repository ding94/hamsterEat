<?php

/* @var $this yii\web\View */
use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Available Controllers';
?>
<style type="text/css">
    .con-div{
        background-color: #ffffff;
        margin: 10px;
        padding:20px;
        width: 30%;
        min-height: 250px;
    }

    .row{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

</style>
<div class="container">
    <div class="row">
        <?php $count = 1;?>
        <?php foreach ($link as $k => $value):?><!-- loop all link -->
            <?php if($k!=0): ?><!-- detect 0 for no offset -1 error -->

                <!-- detect controller whether is same -->
                <?php if($link[$k]['controller'] != $link[$k-1]['controller']): ?>

                    <!-- not same as previous, add controller title-->
                    </div><div class="con-div">
                        <?php $count = 0;?>
                        <h3><?= $value['controller']; ?></h3>
                 <?php endif;?>

                <?php elseif($k==0): ?> <!-- first show controller -->
                    <div class="con-div">
                    <h3><?= $value['controller']; ?></h3>
                <?php endif;?>

                <!-- set all links -->
                <?= Html::a('<font>'.$value['name'].'</font>',[$value['link']]);?><br>
            <?php endforeach;?>
        </div>
    </div>
</div>