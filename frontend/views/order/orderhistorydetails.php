<?php
/* @var $this yii\web\View */
$this->title = "Invoice";
use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\FoodSelectionName;
use common\models\Order\Orders;
use yii\helpers\Html;

?>

<body>
    <div class="col-md-12">
        <div class="row" style="padding-top: 5%;font-family: 'Times New Roman', Times, serif;">
            <div name="titles"s style="padding-bottom: 5%">
                <font style="font-size: 3em;">Invoice</font>
                <p>SGShop Ecommerce Sdn Bhd</p>
                <p>1123326T</p>
                <p>B-GF-05, Medini 6, Jalan Medini Sentral 5, Bandar Medini Iskandar Malaysia,<br>79250 Iskandar Puteri, Johor, Malaysia. </p>
            </div>

            <div class="col-lg-12">
                <table class="table" style="font-size: 1em;">
                    <tr>
                        <td style="width: 15%;font-weight: bold;">Username:</td>
                        <td style="width: 30%;"><?= $order['User_Username']; ?></td>
                        <td style="width: 15%;font-weight: bold;">Delivery ID:</td>
                        <td style="width: 30%;"><?= $order['Delivery_ID']; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Contact No:</td>
                        <td><?= $address['contactno']; ?></td>
                        <td style="font-weight: bold;">Pay Method:</td>
                        <td><?= $order['Orders_PaymentMethod']; ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;text-align: top;">Delivery Address:</td>
                        <td><?= $address['location'].', '.$address['postcode'].', '.$address['area']; ?></td>
                        <td style="font-weight: bold;">Payment Made:</td>
                        <td><?= Yii::$app->formatter->asTime($order['Orders_DateTimeMade']); ?></td>
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
                    <?php foreach ($orderitem as $k => $value): ?>
                        <?php $food = Food::find()->where('Food_ID=:id',[':id'=>$value['Food_ID']])->one(); ?>
                        <?php 
                            $select= array();
                            $selections = Orderitemselection::find()->where('Order_ID=:id',[':id'=>$value['Order_ID']])->all(); 
                            foreach ($selections as $l => $sel) {
                                $foodselect = FoodSelectionName::find()->where("id=:id and language = 'en'",[':id'=>$sel['Selection_ID']])->one();
                                if (!empty($foodselect)) {
                                    $select = $select.$foodselect['translation'].', ';
                                }
                            }
                        ?>
                        <tr>
                            <td rowspan="5" style="border: 1px solid black;text-align: center;"><?= $value['Order_ID']; ?></td>
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
                            <td style="border-bottom: 1px solid #ddd;padding-right: 5%;text-align: right;">RM <?=  number_format($value['OrderItem_LineTotal'],2); ?></td>
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
                        <td style="width:20%;text-align: right;">RM <?=  number_format($order['Orders_Subtotal'],2); ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Delivery Charge:</td>
                        <td style="text-align: right;">RM <?=  number_format($order['Orders_DeliveryCharge'],2); ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Discounted:</td>
                            <td style="text-align: right;">- RM
                    <?php if (!empty($order['Orders_DiscountEarlyAmount'])): echo number_format($order['Orders_DiscountEarlyAmount'],2); ?>
                    <?php elseif(!empty($order['Orders_DiscountTotalAmount'])): echo number_format($order['Orders_DiscountTotalAmount'],2); ?>
                    <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;font-weight: bold;padding-top: 5%;">Total:</td>
                        <td style="text-align: right;font-weight: bold;padding-top: 5%;">RM <?=  number_format($order['Orders_TotalPrice'],2); ?></td>
                    </tr>
                    
                </table>

                
            </div>
        </div>
    </div>
</body>