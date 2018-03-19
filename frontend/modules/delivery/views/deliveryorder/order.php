<?php
/* @var $this yii\web\View */
use common\models\food\Food;
use common\models\Restaurant;
use common\models\RestaurantName;
use yii\helpers\Html;
use frontend\assets\DeliverymanOrdersAsset;
use kartik\widgets\Select2;

$this->title = Yii::t('layouts',"Delivery Orders");
DeliverymanOrdersAsset::register($this);
?>
<div class="container" id="deliveryman-orders-container">
    <div class="deliveryman-orders-header">
        <div class="deliveryman-orders-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <div class="dropdown-url">
                 <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => Yii::t('common','Go To ...'),
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
            <div class="nav-url">
                <ul id="deliveryman-orders-nav" class="nav nav-pills nav-stacked">
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="Deliveryman Orders" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-delivery',$name) ?></a>
                        </li>
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="deliveryman-orders-content" class="col-sm-10">
            <?php if($record->result == 1):?>
                <h3>You can receive delivery orders for today!</h3>
              <?=Html::a(Yii::t('m-delivery','Already Sign In'),['/Delivery/daily-sign-in/signin'],['class' => 'raised-btn alternative-btn btn-lg btn-signin', 'disabled' =>"true"]);?>
            <?php else :?>
                <h3>Sign in to receive delivery orders!</h3>
              <?=Html::a(Yii::t('common','Sign In'),['/Delivery/daily-sign-in/signin'],['class' => 'raised-btn main-btn btn-lg btn-signin']);?>
            <?php endif ;?>
            <?php 
                if (empty($dman)){
            ?>
                <h3><?= Yii::t('m-delivery','You have no orders to deliver at the moment.')?></h3>
            <?php
                } else {
                foreach ($dman as $did => $orderdetails) : 
            ?>
            <table class="table table-user-info deliveryman-orders-table">
                <thead>
                    <tr>
                        <th><?= Yii::t('common','Delivery ID')?></th>
                        <th><?= Yii::t('m-delivery','Order Address')?></th>
                        <th><?= Yii::t('m-delivery','Order Postcode')?></th>
                        <th><?= Yii::t('m-delivery','Collect')?> (RM)</th>
                        <th><?= Yii::t('m-delivery','View Map')?></th>
                        <th>Status</th>
                    </tr>
                </thead>
                <?php
                    $order = $orderdetails['order'];
                    $address = $orderdetails['address'];    
                    
                ?>
                <tr>
                    <td data-th="Delivery ID"><?php echo $did; ?></td>
                    <td data-th="Order Address"><?= $address['location']; ?></td>
                    <td data-th="Order Postcode"><?= $address['postcode']; ?></td>
                    <?php if ($order['Orders_PaymentMethod'] != 'Cash on Delivery')
                    { ?>
                        <td data-th="Collect (RM)"><?php echo "0.00"; ?></td>
                    <?php }
                    else
                    { ?>
                        <td data-th="Collect (RM)"><?php echo $order['Orders_TotalPrice']; ?></td>
                    <?php } ?>
                    <td data-th="View Map">
                        <a class='raised-btn secondary-btn' target='_blank' href='http://maps.google.com/maps?daddr=<?php echo $address['location']; ?>,+<?php echo $address['postcode']; ?>,+Malaysia&amp;ll='><?= Yii::t('m-delivery','Show Location')?></a>
                    </td>
                    <td data-th="Status">
                        <?php 
                            if($order->Orders_Status == 5):
                                echo Html::a(Yii::t('order','Completed'), ['update-completed', 'did'=>$did], ['class'=>'raised-btn main-btn']); 
                            else : 
                                echo $statusid[$order->Orders_Status];
                        endif;?>
                    </td>
                </tr>
                <thead>
                    <tr>
                        <th><?= Yii::t('m-restaurant','Restaurant Name')?></th>
                        <th colspan="2"><?= Yii::t('common','Area')?></th>
                        <th><?= Yii::t('common','Quantity')?></th>
                        <th><?= Yii::t('m-delivery','Current Status')?></th>
                        <th><?= Yii::t('m-restaurant','Update Status')?></th>
                    </tr>
                </thead>
                <?php
               
                    
                foreach ($orderdetails['item'] as $orderitemdetails) :
                ?>
                    <tr>
                        <?php
                            $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one();
                            $restname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$foodname['Restaurant_ID']])->one();
                            $cookies = Yii::$app->request->cookies;
                            $resname = RestaurantName::find()->where('rid=:rid',[':rid'=>$restname['Restaurant_ID']])->andWhere(['=','language',$cookies['language']->value])->one();
                        ?>
                        <td data-th="Restaurant Name"><?php echo $resname['translation']; ?></td>
                        <td colspan="2" data-th="Area"><?php echo $restname['Restaurant_Area']; ?></td>
                        <td data-th="Quantity"><?php echo $orderitemdetails['OrderItem_Quantity']; ?></td>
                        
                        <td><?= $statusid[$orderitemdetails['OrderItem_Status']];?></td>
                        <td data-th="Current Status">
                        <?php if ($orderitemdetails['OrderItem_Status'] == 2 || $orderitemdetails['OrderItem_Status']== 3): ?>
                            <span class='label label-warning'><?= Yii::t('m-delivery','Wait for Food to be Prepared')?> </span>

                        <?php elseif($orderitemdetails['OrderItem_Status']== 4): ?>
                           <?php echo Html::a(Yii::t('order','Picked Up'), ['update-pickedup', 'oid'=>$orderitemdetails['Order_ID'], 'did'=>$did], ['class'=>'raised-btn main-btn']); ?>
                        <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; } ?>
        </div>
    </div>
</div>