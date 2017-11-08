<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use yii\helpers\Html;
use frontend\controllers\CartController;
use frontend\assets\OrderDetailsAsset;

$this->title = "Order Details For Delivery ID: ".$did;
OrderDetailsAsset::register($this);
?>

<div id="order-details-container" class = "container">
    <div class="order-details-header">
        <div class="order-details-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <ul id="order-details-nav" class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("Back",['order/my-orders'],['class'=>'btn-block'])?></li>
            </ul>
        </div>
        <div id="order-details-content" class="col-sm-10">
        <table class="table table-user-info" style="border:1px solid black;">
            <tr>
                <th><center> Address </th>
                <td><center> <?php echo $address; ?> </td>
                <th><center> Status </th>
                <td><center> <?php echo $label; ?> </td>
            </tr>
            <tr>
                <th><center> Receiving Date </th>
                <td><center> <?php echo $date; ?> </td>
                <th><center> Receiving Time </th>
                <td><center> <?php echo $time; ?> </td>
            </tr>
            <tr>
                <th><center> Payment Method </th>
                <td><center> <?php echo $paymethod; ?> </td>
                <th><center> Time Placed </th>
                <td><center> <?php echo $timeplaced; ?> </td>
            </tr>
        </table>
        <table class="table table-user-info" style="border:1px solid black;">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th colspan ="2">Food Name</th>
                    <th>Unit Price (RM)</th>
                    <th>Quantity</th>
                    <th>Selections</th>
                    <th>Selections Price (RM)</th>
                    <th>LineTotal (RM)</th>
                    <th colspan ="2">Remarks</th>
                </tr>
            </thead>
        <?php 
        foreach ($orderitemdetails as $orderitemdetails) :
            $fooddetails = food::find()->where('Food_ID = :fid',[':fid'=>$orderitemdetails['Food_ID']])->one();
        ?>
            <tr>
            <td class="with" data-th="Order ID"><?php echo $orderitemdetails['Order_ID']; ?></td>
            <td><?php echo Html::img('@web/imageLocation/foodImg/'.$fooddetails['PicPath'], ['class' => 'img-responsive','style'=>'height:60px; width:90px; margin:auto;']); ?></td>
            <td class="with" data-th="Food Name"><?php echo $fooddetails['Name']; ?></td>
            <td class="with" data-th="Unit Price (RM)"><?php echo CartController::actionRoundoff1decimal($fooddetails['Price']); ?></td>
            <td class="with" data-th="Quantity"><?php echo $orderitemdetails['OrderItem_Quantity']; ?></td>
            <?php 
            $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$orderitemdetails['Order_ID']])->all();
            ?>
            <td class="with" data-th="Selections">
            <?php 
            foreach ($selections as $selections) :
              $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
              $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
              if (!is_null($selectionname))
              {
                echo $selectiontype['TypeName'].': &nbsp;'.$selectionname['Name'];
                echo "<br>";
              }

            endforeach;
            ?>
            </td>
            <td class="with" data-th="Selections Price (RM)"><?php echo CartController::actionRoundoff1decimal($orderitemdetails['OrderItem_SelectionTotal']); ?></td>
            <td class="with" data-th="LineTotal (RM)"><?php echo CartController::actionRoundoff1decimal($orderitemdetails['OrderItem_LineTotal']); ?></td>
            <td  class="with" data-th="Remarks" colspan ="2"><?php echo $orderitemdetails['OrderItem_Remark']; ?></td>
            </tr>
        <?php
          endforeach;
          $did = Orders::find()->where('Delivery_ID = :did',[':did'=>$did])->one();
          ?>
            <tr>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td><strong><center> Subtotal (RM): </strong></td>
                <td colspan ="2"><center><?php echo CartController::actionRoundoff1decimal($did['Orders_Subtotal']); ?></td>
            </tr>
            <tr>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td><strong><center> Delivery Charge (RM): </strong></td>
                <td colspan = 2><center><?php echo CartController::actionRoundoff1decimal($did['Orders_DeliveryCharge']); ?></td>
            </tr>
            <tr>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td><strong><center> Early Discount (RM): </strong></td>
                <td colspan ="2"><center> -<?php echo CartController::actionRoundoff1decimal($did['Orders_DiscountEarlyAmount']); ?></td>
            </tr>
            <tr>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td class="none"> </td>
                <td><strong><center> Total (RM): </strong></td>
                <td colspan ="2"><center><strong><?php echo CartController::actionRoundoff1decimal($did['Orders_TotalPrice']); ?></strong></td>
            </tr>
            </table>
        </div>
    </div>
</div>