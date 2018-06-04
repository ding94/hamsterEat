<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\FoodSelectionName;
use common\models\Order\Orders;
use frontend\controllers\CommonController;
use frontend\controllers\CartController;
use yii\helpers\Html;
$this->title = "Invoice";

//Check last Delivery ID for adding 'page break'
$last_key = end($order);
    
?>
<body>

<?php foreach ($order as $kk => $allorder): ?>

       
    <div class="col-md-12">
        <div class="row" style="padding-top: 5%;font-family: 'Times New Roman', Times, serif;">
            <div name="titles"s style="padding-bottom: 5%">
                <font style="font-size: 3em;">Invoice</font>
                <p>SGshop Ecommerce Sdn Bhd (1123326-T)</p>
                <p>B-GF-05, Medini 6, Jalan Medini Sentral 5, Bandar Medini Iskandar Malaysia,<br>79250 Iskandar Puteri, Johor, Malaysia. </p>
            </div>

            <div class="col-lg-12">
                <table class="table" style="font-size: 1em;">
                    <tr>
                        <td style="width: 15%;font-weight: bold;">Username:</td>
                        <td style="width: 30%;"><?= $allorder['User_Username']; ?></td>
                        <td style="width: 15%;font-weight: bold;">Delivery ID:</td>
                        <td style="width: 30%;"><?= $allorder['Delivery_ID']; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Contact No:</td>
                        <td><?= $address['contactno']; ?></td>
                        <td style="font-weight: bold;">Pay Method:</td>
                        <td><?= $allorder['Orders_PaymentMethod']; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;text-align: top;">Delivery Address:</td>
                        <td><?= $address['location'].', '.$address['postcode'].', '.$address['area']; ?></td>
                        <td style="font-weight: bold;">Payment Made:</td>
                        <td><?= CommonController::getTime($allorder['Orders_DateTimeMade']); ?></td>
                    </tr> 
                </table>
            </div>       
            <div name="titles"s style="padding-top: 5%">
                <font style="font-size: 2.5em;">Items</font>
            </div>
            <div class="col-lg-12">
                <table class="table" style="font-size: 1em;border-collapse: collapse;border: 1px solid black;">
                        <tr>
                            <td style="font-weight: bold;width: 15%;border: 1px solid black;">Order ID</td>
                            <td colspan="3" style="width: 85%;border: 1px solid black;"></td>
                        </tr>
                    
                        <?php foreach ($allorder['order_item'] as $kk => $value): ?>    
                        <?php $food = Food::find()->where('Food_ID=:id',[':id'=>$value['Food_ID']])->one(); ?>
                        <?php 
                            $select= '';

                            $selections = Orderitemselection::find()->where('Order_ID=:id',[':id'=>$value['Order_ID']])->all(); 
                            foreach ($selections as $l => $sel) {
                                $foodselect = FoodSelectionName::find()->where("id=:id and language = 'en'",[':id'=>$sel['Selection_ID']])->one();
                                if (!empty($foodselect)) {
                                    $select = $select.$foodselect['translation'].', ';
                                }
                            }
                        ?>
                        <tr>
                            <td rowspan="5" style="border: 1px solid black;text-align: center;">
                                <?= $value['Order_ID']; ?>
                                <?php if(!empty($nicknames) && !empty($nicknames[$value['Order_ID']])) : ?>
                                    <hr>
                                    Nickname(s):
                                    <?php foreach ($nicknames[$value['Order_ID']] as $k => $nickname) : ?>
                                        <br>
                                        <?= ($k+1).'.'.$nickname; ?>
                                    <?php endforeach; ?>
                                <?php endif;?>
                            </td>
                            <td style="width:15%;font-weight: bold;border-bottom: 1px solid #ddd;">Food</td>
                            <td style="width:50%;border-bottom: 1px solid #ddd;"><?= $food['originName']; ?></td>
                            <td style="width:10%;border-bottom: 1px solid #ddd;padding-right: 5%;text-align: right;">RM <?=  number_format($food['Price'],2); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;border-bottom: 1px solid #ddd;">Selections</td>
                            <td style="border-bottom: 1px solid #ddd;"><?= $select; ?></td>
                            <td style="border-bottom: 1px solid #ddd;padding-right: 5%;text-align: right;">RM <?=  number_format($value['OrderItem_SelectionTotal'],2); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;border-bottom: 1px solid #ddd;">Quantity</td>
                            <td style="border-bottom: 1px solid #ddd;"></td>
                            <td style="border-bottom: 1px solid #ddd;padding-right: 5%;text-align: right;"><?= $value['OrderItem_Quantity']; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;border-bottom: 1px solid #ddd;">Line Total</td>
                            <td style="border-bottom: 1px solid #ddd;"></td>
                            <td style="border-bottom: 1px solid #ddd;padding-right: 5%;text-align: right;">RM <?=  number_format(($value['OrderItem_LineTotal']+$value['OrderItem_SelectionTotal'])*$value['OrderItem_Quantity'],2); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;border-bottom: 1px solid black">Remarks</td>
                            <td colspan="2" style="border-bottom: 1px solid black"><?= $value['OrderItem_Remark']; ?></td>
                        </tr>
                    
                     <?php endforeach;?>
                </table>

                <table class="table" align="right" style="width:75%;font-size: 1em;margin-right: 5%;">
                    
                        <tr>
                            <td style="text-align: right;">Subtotal:</td>
                            <td style="width:20%;text-align: right;">RM <?=  number_format($allorder['Orders_Subtotal'],2); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">Delivery Charge:</td>
                            <td style="text-align: right;">RM <?=  number_format($allorder['Orders_DeliveryCharge'],2); ?></td>
                        </tr>
                        <tr>
                            <?php if (!empty($allorder['Orders_DiscountEarlyAmount'])): ?>
                                <td style="text-align: right;">Discounted:</td>
                                <td style="text-align: right;">- RM<?= number_format($allorder['Orders_DiscountEarlyAmount'],2); ?></td>
                            <?php elseif(!empty($allorder['Orders_DiscountTotalAmount'])): ?>
                                <td style="text-align: right;">Discounted:</td>
                                <td style="text-align: right;"><?php echo number_format($allorder['Orders_DiscountTotalAmount'],2); ?></td>
                            <?php endif ?>
                        </tr>
                        <?php if($allorder['Orders_TotalPrice'] != CartController::actionRoundoff1decimal($allorder['Orders_TotalPrice'])): ?>
                        <tr>
                             <td style="text-align: right;">Round off:</td>
                             <td style="text-align: right;">
                                <?php if($allorder['Orders_TotalPrice'] > CartController::actionRoundoff1decimal($allorder['Orders_TotalPrice'])): echo "- RM ".number_format($allorder['Orders_TotalPrice'] - CartController::actionRoundoff1decimal($allorder['Orders_TotalPrice']),2); ?>

                                <?php elseif($allorder['Orders_TotalPrice'] < CartController::actionRoundoff1decimal($allorder['Orders_TotalPrice'])): echo "+ RM ".number_format(CartController::actionRoundoff1decimal($allorder['Orders_TotalPrice']) - $allorder['Orders_TotalPrice'],2); ?>
                                <?php endif ?>  
                            </td>
                        </tr>
                        <?php endif ?>
                        <tr>
                            <td style="text-align: right;font-weight: bold;padding-top: 5%;">Total:</td>
                            <td style="text-align: right;font-weight: bold;padding-top: 5%;">RM <?=  CartController::actionRoundoff1decimal($allorder['Orders_TotalPrice']); ?></td>
                        </tr>
                   
                </table>

                
            </div>
        </div>
    </div>  
    <?php 
        if ($allorder['Delivery_ID'] == $last_key['Delivery_ID']) {
            // last element
        } else {
            // not last element
              echo '<pagebreak />';
        }
    ?> 

<?php endforeach;?>
</body>
