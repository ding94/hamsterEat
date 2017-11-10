<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use common\models\Orderitem;
use common\models\Restaurant;
use yii\helpers\Html;
use frontend\assets\DeliverymanOrdersHistoryAsset;

$this->title = "Deliveryman Order's History";
DeliverymanOrdersHistoryAsset::register($this);
?>
<div class="container" id="deliveryman-orders-history-container">
    <div class="deliveryman-orders-history-header">
        <div class="deliveryman-orders-history-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <ul id="deliveryman-orders-history-nav" class="nav nav-pills nav-stacked">
                <li role="presentation"><?php echo Html::a("Deliveryman Orders",['order/deliveryman-orders'],['class'=>'btn-block'])?></li>
                <li role="presentation" class="active"><?php echo Html::a("Deliveryman Orders History",['order/deliveryman-order-history'],['class'=>'btn-block'])?></li>
            </ul>
        </div>
        <div id="deliveryman-orders-history-content" class="col-sm-10">
            <?php
                foreach ($dman as $dman) :
            ?>
            <table class="table table-user-info deliveryman-orders-history-table">
                <thead>
                    <tr>
                        <th>Delivery ID</th>
                        <th>Username</th>
                        <th>Date to be Received</th>
                        <th>Time to be Received</th>
                        <th>Current Status</th>
                        <th>Time Placed</th>
                        <th>Collect (RM)</th>
                    </tr>
                </thead>
                    <?php
                        $orderdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$dman['Delivery_ID']])->one();
                    ?>
                    <tr>
                        <td data-th="Delivery ID"><?php echo $orderdetails['Delivery_ID']; ?></td>
                        <td data-th="Username"><?php echo $orderdetails['User_Username']; ?></td>
                        <td data-th="Date to be Received"><?php echo $orderdetails['Orders_Date']; ?></td>
                        <td data-th="Time to be Received"><?php echo $orderdetails['Orders_Time']; ?></td>
                        <?php
                            if ($orderdetails['Orders_Status']== 'Rating Done')
                            {
                        ?>
                        <td data-th="Current Status"><span class="label label-success"><?php echo $orderdetails['Orders_Status']; ?></span></td>
                        <?php
                            } 
                            elseif ($orderdetails['Orders_Status']== 'Completed')
                            {
                        ?>
                        <td data-th="Current Status"><span class="label label-success"><?php echo $orderdetails['Orders_Status']; ?></span></td>
                        <?php
                            }
                            date_default_timezone_set("Asia/Kuala_Lumpur");
                            $timeplaced = date('d/m/Y H:i:s', $orderdetails['Orders_DateTimeMade']);
                        ?>
                        <td data-th="Time Placed"><?php echo $timeplaced; ?></td>
                        <td data-th="Collect (RM)"><?php echo $orderdetails['Orders_TotalPrice']; ?></td>
                    </tr>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Restaurant Name</th>
                            <th colspan="2">Restaurant Address</th>
                            <th>Quantity</th>
                            <th colspan="2">Current Status</th>
                        </tr>
                    </thead>
                    <?php
                        $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$orderdetails['Delivery_ID']])->orderBy(['Order_ID'=>SORT_ASC])->all();
                        foreach($orderitemdetails as $orderitemdetails) :
                    ?>
                    <tr>
                        <td data-th="Order ID"><?php echo $orderitemdetails['Order_ID']; ?></td>
                        <?php
                            $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one();
                            $restname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$foodname['Restaurant_ID']])->one();
                        ?>
                        <td data-th="Restaurant Name"><?php echo $restname['Restaurant_Name']; ?></td>
                        <td colspan="2" data-th="Restaurant Address"><?php echo $restname['Restaurant_UnitNo'].', '.$restname['Restaurant_Street'].', '.$restname['Restaurant_Area'].', '.$restname['Restaurant_Postcode']; ?></td>
                        <td data-th="Quantity"><?php echo $orderitemdetails['OrderItem_Quantity']; ?></td>
                        <td colspan="2" data-th="Current Status"><span class="label label-info"><?php echo $orderitemdetails['OrderItem_Status']; ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php endforeach; ?>
        </div>
    </div>
</div>