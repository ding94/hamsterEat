<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\FoodSelectionName;
use common\models\Order\Orders;
use frontend\controllers\CommonController;
use yii\helpers\Html;
$this->title = "Orders List";
?>

<body>
    <div class="col-md-12">
        <div class="row" style="padding-top: 5%;font-family: 'Times New Roman', Times, serif;">
            <div name="titles"s style="padding-bottom: 5%">
                <font style="font-size: 3em;">Orders List</font>
            <div class="col-lg-12">
                <?php foreach ($data as $restaurant => $companies): ?>
                    <table class="table" style="border: 1px solid black;">
                        <tr style="border: 1px solid black; border-collapse: collapse;">
                            <td colspan="7" style="border: 1px solid black;"><h3><?= $restaurant?></h3></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;font-weight: bold">Company</td>
                            <td style="border: 1px solid black;font-weight: bold">Delivery ID</td>
                            <td style="border: 1px solid black;font-weight: bold">Order ID</td>
                            <td style="border: 1px solid black;font-weight: bold">Food</td>
                            <td style="border: 1px solid black;font-weight: bold">Nickname</td>
                            <td style="border: 1px solid black;font-weight: bold">Contact</td>
                            <td style="border: 1px solid black;font-weight: bold">Contact No</td>
                            <td style="border: 1px solid black;font-weight: bold">check</td>
                        </tr>
                        <?php foreach($companies as $company => $did): ?>
                            <?php foreach($did as $l => $items): ?>
                                <?php $count = count($items); ?>
                                <tr style="border: 1px solid black;">

                                    <td  rowspan=<?= $delirowcount[$restaurant][$l]+$count+1 ?> style="border: 1px solid black;"><?= $company?></td>
                                    <td  rowspan=<?= $delirowcount[$restaurant][$l]+$count+1 ?> style="border: 1px solid black;"><?= $l?></td>
                                </tr>
                                <?php foreach($items as $k => $item): ?>
                                    <tr>
                                        <td rowspan=<?= $orderrowcount[$item['Order_ID']]+1 ?> style="border: 1px solid black;">
                                            <?= $item['Order_ID']; ?>
                                        </td>
                                        <td rowspan=<?= $orderrowcount[$item['Order_ID']]+1 ?> style="border: 1px solid black;">
                                            <b><?= $item['food']['originName']?>,</b><?=$item->getFood_selection_name($item); ?>
                                        </td>
                                        <td rowspan=<?= $orderrowcount[$item['Order_ID']]+1 ?> style="border: 1px solid black;">
                                            <?= $item['address']['name']; ?>
                                        </td>
                                        <td rowspan=<?= $orderrowcount[$item['Order_ID']]+1 ?> style="border: 1px solid black;">
                                            <?= $item['address']['contactno']; ?>
                                        </td>
                                    </tr>
                                    <?php for ($i=0; $i < $orderrowcount[$item['Order_ID']]; $i++) { ?>
                                            <tr style="border: 1px solid black;">
                                                <td style="border: 1px solid black;">
                                                    <?php if (empty($item['nickname'][$i]['nickname'])):?>
                                                        <?= 'blank' ?>
                                                    <?php else: ?>
                                                        <b><?= $item['nickname'][$i]['nickname']; ?></b>
                                                    <?php endif; ?>
                                                </td>
                                                <td style="border: 1px solid black;"></td>
                                            </tr>
                                        <?php }?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </table>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
</body>