<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use frontend\controllers\CartController;
$this->title = "Order Placed";
?>

<body>
    <div class="col-md-12" id="aftercheckout">
        <div class="row" style="margin-top: 5%;">
            <div class="col-lg-5" style="margin: 0px 4% 0px 6%; background-color: white;">
                <table class="table table-hover" style="font-size: 1.2em; font-family: 'Times New Roman', Times, serif;">
                    <tr>
                        <td style="width: 40%;">Delivery ID:</td>
                        <td colspan="2"><?= $order['Delivery_ID']; ?></td>
                    </tr>
                    
                        <?php foreach ($orderitem as $key => $oid): ?>
                            <tr>
                                <td><?= $key+1; ?>.Order ID:</td>
                                <td><?= $oid['Order_ID']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <tr>
                        <td>Delivery Location:</td>
                        <td colspan="2"><?= $order['Orders_Location'].', '.$order['Orders_Postcode'].', '.$order['Orders_Area']; ?></td>
                    </tr>
                    <tr>
                        <td>Recipient:</td>
                        <td colspan="2"><?= $order['User_fullname']; ?></td>
                    </tr>
                    <tr>
                        <td>Contact No:</td>
                        <td colspan="2"><?= $order['User_contactno']; ?></td>
                    </tr>
                </table>
                <br>
                <table class="table table-hover" style="font-size: 1.2em; font-family: 'Times New Roman', Times, serif;">
                        <tr>
                            <td></td>
                            <td style="text-align: right">Subtotal:</td>
                            <td>RM <?= number_format($order['Orders_Subtotal'],2); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: right">Delivery Charge:</td>
                            <td>RM <?= number_format($order['Orders_DeliveryCharge'],2); ?></td>
                        </tr>
                        <tr style="background-color: #d9d9d9">
                            <td></td>
                            <td style="text-align: right;">Total:</td>
                            <td>RM <?= number_format($order['Orders_TotalPrice'],2); ?></td>
                        </tr>
                </table>
            </div>
            <div class="col-lg-5" style="font-family: 'Times New Roman', Times, serif;background-color: white; height: 54%;padding-top: 7%;" >
                <?php if ($order['Orders_PaymentMethod'] == "Cash on Delivery"): ?>

                    <center>
                        <font style="font-size: 2em;">
                            Thank you for placing order with us!<br>Your order has been made<br><br>
                        </font>
                        <font style="font-size: 2em;background-color: #ffffb3">
                            Please Prepare RM <?= number_format($order['Orders_TotalPrice'],2); ?> to our rider.
                        </font>
                    </center>

                <?php elseif($order['Orders_PaymentMethod'] == "Account Balance"): ?>
                    <center>
                        <font style="font-size: 2em;">
                            Thank you for placing order with us!<br>Your order has been made<br><br>
                        </font>
                        <font style="font-size: 2em;background-color: #ffffb3">
                            You have paid RM <?= number_format($order['Orders_TotalPrice'],2); ?> with your account balance.
                        </font>
                    </center>

                <?php endif?>
            </div>
        </div>
        <div style="margin-top: 3%;">
            <center><?php echo Html::a('More Detail', ['/order/order-details','did'=>$order['Delivery_ID']], ['class'=>'btn btn-primary'])?></center>
        </div>
    </div>
</body>