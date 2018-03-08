<?php
/* @var $this yii\web\View */
use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\Order\Orderitem;
use common\models\food\Foodselection;
use common\models\Order\Orders;
use common\models\Order\DeliveryAddress;
use yii\helpers\Html;
use common\models\RestaurantName;
$cookies = Yii::$app->request->cookies;

$resname = RestaurantName::find()->where('rid=:rid',[':rid'=>$level['Restaurant_ID']])->andWhere(['=','language',$cookies['language']->value])->one();
$this->title = "Orders List for ". $resname['translation'];
?>

<body>
    <div class="col-md-12">
        <div class="row" style="padding-top: 5%;font-family: 'Times New Roman', Times, serif;">
            <font style="font-size: 3em;"><?= $this->title; ?></font>
            <?php if(!empty($allData)): ?>
            <div class="col-lg-12">
                <?php $j=0; ?>
                <?php foreach ($allData as $fid => $data): ?>
                     <table class="table" style="font-size: 1em;">
                        <tr>
                            <td rowspan=<?php echo (count($data)+1)*2; ?> style="width:10%"> <?= Food::find()->where('Food_ID=:fid',[':fid'=>$fid])->one()->Name; ?> </td>
                        </tr>
                        <tr>
                            <td style="width:10%">Order ID</td>
                            <td style="width:25%">Selection</td>
                            <td style="width:10%">Quantity</td>
                            <td style="width:35%">Remark</td>
                            <td style="width:10%">Status</td>
                        </tr>
                        <?php foreach ($data as $k => $values) : ?>
                        <?php $oids[$j]=$values['Order_ID']; $j+=1; ?>
                        <tr>
                            <td><?= $values['Order_ID']; ?></td>
                            <td><?php $select=""; foreach($values['order_selection'] as $os => $value){
                                    $type = Foodselection::find()->where('ID=:id',[':id'=>$value['FoodType_ID']])->one();
                                    $sel = Foodselection::find()->where('ID=:id',[':id'=>$value['Selection_ID']])->one();
                                    
                                    if (!empty($type['TypeName'])) {$select .= $type['TypeName'].' ';}
                                    if (!empty($sel['Name'])) {$select .= $sel['Name'].', ';}
                                } echo $select;
                                ?>
                            </td>
                            <td><?= $values['OrderItem_Quantity']; ?></td>
                            <td><?= $values['OrderItem_Remark']; ?></td>
                            <td><?= $values['OrderItem_Status']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endforeach; ?>
                
            </div>

            <div class="col-lg-12">
                <table class="table" style="font-size: 1em;width: 40%">
                    <tr>
                            <th style="width:10%">Order ID</th>
                            <th style="width:10%">Recipient</th>
                            <th style="width:10%">Delivery ID</th>
                        </tr>

                    <?php foreach($oids as $o => $oid): ?>
                        <?php 
                            $did = Orderitem::find()->where('Order_ID=:oid',[':oid'=>$oid])->one()->Delivery_ID; 
                            $delivery = DeliveryAddress::find()->where('Delivery_ID=:did',[':did'=>$did])->one();?>
                        
                        <tr>
                            <td><?= $oid; ?></td>
                            <td><?= $delivery['name']; ?></td>
                            <td><?= $did; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <h1>No orders today</h1>
        <?php endif; ?>
        </div>
    </div>
</body>