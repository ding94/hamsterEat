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
                            <td colspan="5" style="border: 1px solid black;"><h3><?= $restaurant?></h3></td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;font-weight: bold">Company</td>
                            <td style="border: 1px solid black;font-weight: bold">Order ID</td>
                            <td style="border: 1px solid black;font-weight: bold">Food</td>
                            <td style="border: 1px solid black;font-weight: bold">Contact Person</td>
                            <td style="border: 1px solid black;font-weight: bold">Contact Number</td>
                        </tr>
                        <?php foreach($companies as $company => $did): ?>
                                <?php foreach($did as $l => $items): ?>
                                <?php $count = count($items); ?>
                                <tr style="border: 1px solid black;">
                                    <td rowspan=<?= $count+1 ?> style="border: 1px solid black;"><?= $company?></td>
                                </tr>
                                <?php foreach($items as $k => $item): ?>
                                <tr style="border: 1px solid black;">
                                    <td style="border: 1px solid black;"><?= $item['Order_ID']; ?></td>
                                    <td style="border: 1px solid black;"><?= $item['food']['originname']; ?></td>
                                    <td style="border: 1px solid black;"><?= $item['address']['name']; ?></td>
                                    <td style="border: 1px solid black;"><?= $item['address']['contactno']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </table>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>