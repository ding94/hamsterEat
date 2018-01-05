<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Order\Orders;
use yii\helpers\Html;
use frontend\controllers\CartController;
use frontend\assets\OrderDetailsAsset;

$this->title = "Order Details For Delivery ID: ".$did;
OrderDetailsAsset::register($this);
?>
<div class="order">
<div id="order-details-container" class = "container">
    <div class="order-details-header">
        <div class="order-details-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <ul id="order-details-nav" class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("<i class='fa fa-chevron-left'></i> Back",['order/my-orders'],['class'=>'btn-block'])?></li>
            </ul>
        </div>
        <div id="order-details-content" class="col-sm-10">
        <table class="table table-user-info" style="border:1px solid black;">
            <tr>
                <th><center> Address </th>
                <td colspan="3"><?php echo $order['address']['fulladdress']; ?> </td>
            </tr>
            <tr>
                <th><center> Approximate Receiving Time </th>
                <td><?php $date = strtotime($order['Orders_Date'].' '.$order['Orders_Time']); echo date('d M Y h:i:s A',$date);?> </td>
                <th><center> Status </th>
                <td> <?= $label[$order->Orders_Status]['label']; ?> </td>
            </tr>
            <tr>
                <th><center> Payment Method </th>
                <td><?php echo $order['Orders_PaymentMethod']; ?> </td>
                <th><center> Time Placed </th>
                <td><?php echo date('d M Y h:i:s A',$order['Orders_DateTimeMade']); ?> </td>
            </tr>
        </table>
            <?php foreach ($orderitems as $k => $detail): 
                $food = food::find()->where('Food_ID = :id',[':id'=>$detail['Food_ID']])->one();?>

            <table class="table" id="table-border">
                <tr>
                   <th id="cell-border">Order ID</th> 
                   <th colspan="3" id="cell-border"><?php echo $detail['Order_ID']; ?></th>
                </tr>
                <tr>
                    <td rowspan="6" class="vertical-center"><?php echo Html::img($food->singleImg, ['style'=>'height:100px; width:100px;']); ?></td>
                </tr>
                <tr>
                    <td>Food Name:</td>
                    <td><?php echo $food['Name']; ?></td>
                    <td>RM <?php echo $food['Price']; ?></td>
                </tr>
                <tr>
                    <td>Selections:</td>
                    <td colspan="2">
                        <?php
                            $selects = Orderitemselection::find()->where('Order_ID=:id',[':id'=>$detail['Order_ID']])->all();
                            $show = "";
                            foreach ($selects as $ke => $select) {
                                $sel = Foodselection::find()->where('ID=:sid',[':sid'=>$select['Selection_ID']])->one();
                                if ($ke != 0) {
                                    $show .=", ";
                                }
                                $show .= $sel['Name'];
                            }
                            echo $show;

                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Quantity:</td>
                    <td colspan="2"><?php echo $detail['OrderItem_Quantity']; ?></td>
                </tr>
                <tr>
                    <td>Line Total:</td>
                    <td colspan="2">RM <?php echo number_format($detail['OrderItem_LineTotal'] * $detail['OrderItem_Quantity']); ?></td>
                </tr>
                <tr>
                    <td>Remarks:</td>
                    <td colspan="2"><?php echo $detail['OrderItem_Remark']; ?></td>
                </tr>
            </table>
            <?php endforeach; ?>
            <div class="row">
                <div class="tab-content col-md-5"></div>
                <div class="tab-content col-md-6">
                    <table class="table">
                        <tr>
                            <th>Subtotal:</th>
                            <td><?= $order['Orders_Subtotal']; ?></td>
                        </tr>
                        <tr>
                            <th>Delivery Charge:</th>
                            <td><?= $order['Orders_DeliveryCharge']; ?></td>
                        </tr>
                        <?php if ($order['Orders_DiscountEarlyAmount'] >0): ?>
                            <tr>
                                <th>Early Discount:</th>
                                <td style="color: red;">- <?= $order['Orders_DiscountEarlyAmount']; ?></td>
                            </tr>
                        <?php endif ?>
                        <?php if ($order['Orders_DiscountTotalAmount'] >0): ?>
                            <tr>
                                <th>Discount:</th>
                                <td style="color: red;">- <?= $order['Orders_DiscountTotalAmount']; ?></td>
                            </tr>
                        <?php endif ?>
                        <tr>
                            <th>Total:</th>
                            <td><?= $order['Orders_TotalPrice']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>