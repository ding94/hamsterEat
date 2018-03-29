<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use yii\helpers\Url;
use frontend\controllers\CartController;
use frontend\assets\CheckoutAsset;

$this->title = Yii::t('cart','Order Placed');
CheckoutAsset::register($this);
?>
    <div class="container">
       <div class="checkout-progress-bar">
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?=Yii::t('common','Cart'); ?></span>
         </div>
         <span class="bar done"></span>
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?=Yii::t('common','Checkout'); ?></span>
         </div>
         <span class="bar done"></span>
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title"><?=Yii::t('cart','Completed'); ?></span>
         </div>
       </div> 
    </div>
    <?php if($requireName['value'] == 1):?>
    <div id="nameConfirm">
        <?php 
            echo Html::hiddenInput('url',Url::to(['/user/change-name-contact']));
            echo Html::hiddenInput('name', $requireName['fullname']);
            echo Html::hiddenInput('contactno',$requireName['contactno']);
        ?>
    </div>
    <?php endif;?>
    <div class="container" id="aftercheckout">
        <div class="checkout-header">
            <h2>
                <?=Yii::t('common','Delivery ID'); ?>:
                <?= $order['Delivery_ID']; ?>
            </h2>
        </div>
        <div class="row">
            <div class="col-md-6 checkout-detail" >
                <table class="table table-hover" style="font-size: 1.2em;">
                    <!--<tr id="no-border">
                        <td style="width: 40%;"><?=Yii::t('common','Delivery ID'); ?>:</td>
                        <td colspan="2"><?= $order['Delivery_ID']; ?></td>
                    </tr> -->
                        <td><?=Yii::t('order','Order ID'); ?>:</td>
                        <?php $orders= ""; ?>
                        <?php foreach ($orderitem as $key => $oid): ?>
                                <?php if($key == 0 ): ?>
                                    <?php $orders .= $oid['Order_ID']; ?>
                                <?php else : ?>
                                    <?php $orders.=', '.$oid['Order_ID']; ?>
                                <?php endif; ?>
                        <?php endforeach; ?>

                        <td> <?= $orders; ?>
                    <tr>
                        <td><?=Yii::t('cart','Delivery Location'); ?>:</td>
                        <td colspan="2"><?= $order['address']['fulladdress']; ?></td>
                    </tr>
                    <tr>
                        <td><?=Yii::t('cart','Recipient'); ?>:</td>
                        <td colspan="2"><?= $order['address']['name']; ?></td>
                    </tr>
                    <tr>
                        <td><?=Yii::t('common','Contact No'); ?>:</td>
                        <td colspan="2"><?= $order['address']['contactno']; ?></td>
                    </tr>
                </table>
                <br>
                <table class="table table-hover" style="font-size: 1.2em;">
                        <tr>
                            <td></td>
                            <td class="text-right" style="text-align: right"><?=Yii::t('common','Subtotal'); ?>:</td>
                            <td class="text-right">RM <?= number_format($order['Orders_Subtotal'],2); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-right" style="text-align: right"><?=Yii::t('common','Delivery Charge'); ?>:</td>
                            <td class="text-right">RM <?= number_format($order['Orders_DeliveryCharge'],2); ?></td>
                        </tr>
                        <?php if($order['Orders_DiscountEarlyAmount'] > 0 ) : ?>
                            <tr>
                                <td></td>
                                <td class="text-right"><?=Yii::t('cart','Early Discounted'); ?>:</td>
                                <td class="text-right" style="color: red;">- RM <?= number_format($order['Orders_DiscountEarlyAmount'],2); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if($order['Orders_DiscountTotalAmount'] > 0 ) : ?>
                            <tr>
                                <td></td>
                                <td class="text-right"><?=Yii::t('cart','Discounted'); ?>:</td>
                                <td class="text-right" style="color: red;">- RM <?= number_format($order['Orders_DiscountTotalAmount'],2); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr style="background-color: #d9d9d9">
                            <td></td>
                            <td class="text-right" style="text-align: right;"><?=Yii::t('common','Total'); ?>:</td>
                            <td class="text-right">RM <?= number_format($order['Orders_TotalPrice'],2); ?></td>
                        </tr>
                </table>
            </div>
            <div class="col-md-5 checkout-cash" >
                <center>
                    <font style="font-size: 2em;">
                        <?=Yii::t('cart','Thank you for placing order with us!'); ?><br><?=Yii::t('cart','Your order has been made'); ?><br><br>
                    </font>

                    <?php if($order['Orders_PaymentMethod'] == "Cash on Delivery"): ?>

                        <font style="font-size: 2em;background-color: #ffffb3">
                            <?=Yii::t('cart','Please Prepare'); ?> RM <?= number_format($order['Orders_TotalPrice'],2); ?> <?=Yii::t('cart','to our rider.'); ?>
                        </font>

                    <?php endif?>
                </center>
            </div>
        </div>
        <div class="more-detail">
            <center><?php echo Html::a(Yii::t('cart','More Detail'), ['/order/order-details','did'=>$order['Delivery_ID']], ['class'=>'raised-btn main-btn'])?>
            <?php echo Html::a(Yii::t('cart','Home'), ['/site/index'], ['class'=>'raised-btn secondary-btn'])?></center>
        </div>
    </div>
