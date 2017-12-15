<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use frontend\controllers\CartController;
use frontend\assets\CheckoutAsset;

$this->title = "Order Placed";
CheckoutAsset::register($this);
?>
    <div class="container">
       <div class="checkout-progress-bar">
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title">Cart</span>
         </div>
         <span class="bar done"></span>
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title">Checkout</span>
         </div>
         <span class="bar done"></span>
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title">Completed</span>
         </div>
       </div> 
    </div>
    <div class="container" id="aftercheckout">
        <div class="row">
            <div class="col-md-6 checkout-detail" >
                <table class="table table-hover" style="font-size: 1.2em; font-family: 'Times New Roman', Times, serif;">
                    <tr id="no-border">
                        <td style="width: 40%;">Delivery ID:</td>
                        <td colspan="2"><?= $order['Delivery_ID']; ?></td>
                    </tr>
                        <td>Order ID:</td>
                        
                        <?php $orders=""; ?>
                        <?php foreach ($orderitem as $key => $oid): ?>
                                <?php if($key == 0 ): ?>
                                    <?php $orders .= $oid['Order_ID']; ?>
                                <?php else : ?>
                                    <?php $orders.=', '.$oid['Order_ID']; ?>
                                <?php endif; ?>
                        <?php endforeach; ?>

                        <td> <?= $orders; ?>
                    <tr>
                        <td>Delivery Location:</td>
                        <td colspan="2"><?= $order['address']['fulladdress']; ?></td>
                    </tr>
                    <tr>
                        <td>Recipient:</td>
                        <td colspan="2"><?= $order['address']['name']; ?></td>
                    </tr>
                    <tr>
                        <td>Contact No:</td>
                        <td colspan="2"><?= $order['address']['contactno']; ?></td>
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
                        <?php if($order['Orders_DiscountEarlyAmount'] > 0 ) : ?>
                            <tr>
                                <td></td>
                                <td style="text-align: right;">Early Discounted:</td>
                                <td style="color: red;">- RM <?= number_format($order['Orders_DiscountEarlyAmount'],2); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if($order['Orders_DiscountTotalAmount'] > 0 ) : ?>
                            <tr>
                                <td></td>
                                <td style="text-align: right;">Discounted:</td>
                                <td style="color: red;">- RM <?= number_format($order['Orders_DiscountTotalAmount'],2); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr style="background-color: #d9d9d9">
                            <td></td>
                            <td style="text-align: right;">Total:</td>
                            <td>RM <?= number_format($order['Orders_TotalPrice'],2); ?></td>
                        </tr>
                </table>
            </div>
            <div class="col-md-5 checkout-cash" >
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
                    </center>

                <?php endif?>
            </div>
        </div>
        <div class="more-detail">
            <center><?php echo Html::a('More Detail', ['/order/order-details','did'=>$order['Delivery_ID']], ['class'=>'raised-btn main-btn'])?>
            <?php echo Html::a('Home', ['/site/index'], ['class'=>'raised-btn secondary-btn'])?></center>
        </div>
    </div>
