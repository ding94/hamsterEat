<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Admin;
use frontend\assets\MyVouchersAsset;

$this->title = Yii::t('vouchers','My Vouchers');
MyVouchersAsset::register($this);
?>
<div class="voucher">
    <div class="container" id="voucher-container">
        <div class="voucher-header">
            <div class="voucher-header-title"><?= Html::encode($this->title) ?></div>
        </div>
        <div class="content">
            <div class="col-sm-2">
                <ul id="voucher-nav" class="nav nav-pills nav-stacked">
                    <li role="presentation"><?php echo Html::a(Yii::t('vouchers','My Vouchers'),['index'],['class'=>'btn-block active'])?></li>
                </ul>
            </div>
            <div class="col-sm-10 my-vouchers-table" id="voucher-content">
                <?php $j = 0;   
                if (!empty($model)) { ?>
                    <table class="table table-inverse">
                        <thead>
                            <tr>
                                <th><?= Yii::t('ticket','Serial No.')?></th>
                                <th><?= Yii::t('vouchers','Code')?></th> 
                                <th><?= Yii::t('common','Discount')?></th>
                                <th></th>
                                <th><?= Yii::t('vouchers','Last Available Date')?></th>
                            </tr>
                        </thead>
                  <?php foreach ($uservoucher as $k => $uservou) { ?>
                        <tr>
                            <td data-th="Serial No.">
                                <?php $j+=1; echo $j; ?>
                            </td>
                            <td data-th="Code">
                                <?php echo $uservou['code']; ?>
                            </td>
                            <td data-th="Discount">
                                <?php echo $uservou['discount']; ?>
                            </td>
                            <td data-th="Item">
                                <?php echo Yii::t('vouchers',$uservou['discount_item']); ?>
                            </td>
                            <td data-th="Last Available Date">
                                <?php echo $uservou['endDate']; ?>
                            </td>
                        </tr>
                    <?php  } ?>
                    </table>
                    <?php } elseif (empty($model)) { ?>
                <h3><?= Yii::t('vouchers','non-voucher')?></h3>
                <?php }  ?>
            </div>
        </div>
    </div>
</div>