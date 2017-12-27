<?php
/* @var $this yii\web\View */
use common\models\food\Food;
use common\models\Order\Orderitem;
use common\models\Restaurant;
use yii\helpers\Html;
use frontend\assets\DeliverymanOrdersAsset;
use kartik\widgets\Select2;

$this->title = "Delivery Orders";
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
                            'placeholder' => 'Go To ...',
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
                            <a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                        </li>
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="deliveryman-orders-content" class="col-sm-10">
            <?php if($record->result == 1):?>
                <h3>You can receive delivery orders for today!</h3>
              <?=Html::a('Already Sign In',['Delivery/daily-sign-in/signin'],['class' => 'raised-btn alternative-btn btn-lg btn-signin', 'disabled' =>"true"]);?>
            <?php else :?>
                <h3>Sign in to receive delivery orders!</h3>
              <?=Html::a('Sign In',['Delivery/daily-sign-in/signin'],['class' => 'raised-btn main-btn btn-lg btn-signin']);?>
            <?php endif ;?>
            <?php 
                if (empty($dman)){
            ?>
                <h3>You have no orders to deliver at the moment.</h3>
            <?php
                } else {
                foreach ($dman as $orderdetails) : 
            ?>
            <table class="table table-user-info deliveryman-orders-table">
                <thead>
                    <tr>
                        <th>Delivery ID</th>
                        <th>Time to be Received</th>
                        <th>Order Address</th>
                        <th>Order Postcode</th>
                        <th>Collect (RM)</th>
                        <th>View Map</th>
                    </tr>
                </thead>
                <?php 
                   
                    $location=$orderdetails['address']['location'];
                    $postcode=$orderdetails['address']['postcode'];
                    $district=$orderdetails['address']['area']; 
                ?>
                <tr>
                    <td data-th="Delivery ID"><?php echo $orderdetails['Delivery_ID']; ?></td>
                    <td data-th="Time to be Received"><?php echo $orderdetails['Orders_Time']; ?></td>
                    <td data-th="Order Address"><?php echo $location; ?></td>
                    <td data-th="Order Postcode"><?php echo $postcode; ?></td>
                    <?php if ($orderdetails['Orders_PaymentMethod'] != 'Cash on Delivery')
                    { ?>
                        <td data-th="Collect (RM)"><?php echo "0.00"; ?></td>
                    <?php }
                    else
                    { ?>
                        <td data-th="Collect (RM)"><?php echo $orderdetails['Orders_TotalPrice']; ?></td>
                    <?php } ?>
                    <td data-th="View Map"><a class='raised-btn secondary-btn' target='_blank' href='http://maps.google.com/maps?daddr=<?php echo $location; ?>,+<?php echo $postcode; ?>,+<?php echo $district; ?>,+Malaysia&amp;ll='>Show Location</a></td>
                </tr>
                <thead>
                    <tr>
                        <th>Restaurant Name</th>
                        <th colspan="2">Area</th>
                        <th>Quantity</th>
                        <th>Current Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <?php
                $orderitemdetails = Orderitem::find()->where('Delivery_ID = :did', [':did'=>$orderdetails['Delivery_ID']])->orderBy(['Order_ID'=>SORT_ASC])->all();
                    
                foreach ($orderitemdetails as $orderitemdetails) :
                ?>
                    <tr>
                        <?php
                             $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one();
                            $restname = Restaurant::find()->where('Restaurant_ID = :rid', [':rid'=>$foodname['Restaurant_ID']])->one();
                        ?>
                        <td data-th="Restaurant Name"><?php echo $restname['Restaurant_Name']; ?></td>
                        <td colspan="2" data-th="Area"><?php echo $restname['Restaurant_Area']; ?></td>
                        <td data-th="Quantity"><?php echo $orderitemdetails['OrderItem_Quantity']; ?></td>
						
                        <td><?= $statusid[$orderitemdetails['OrderItem_Status']];?></td>

                        <?php if ($orderitemdetails['OrderItem_Status'] == 2): ?>
                            <td data-th="Current Status"><span class='label label-warning'> Wait for Food to be Prepared </span></td>
                        <?php elseif($orderitemdetails['OrderItem_Status']== 3): ?>
                            <td data-th="Current Status"><span class='label label-warning'> Wait for Food to be Prepared </span></td>
                        <?php elseif($orderitemdetails['OrderItem_Status']== 4): ?>
                            <td data-th="Update Status"><?php echo Html::a('Picked Up', ['update-pickedup', 'oid'=>$orderitemdetails['Order_ID'], 'did'=>$orderdetails['Delivery_ID']], ['class'=>'raised-btn main-btn']); ?></td>
                        <?php elseif ($orderdetails['Orders_Status'] == 5): ?>
                            <td data-th="Update Status"><?php echo Html::a('Completed', ['update-completed', 'oid'=>$orderitemdetails['Order_ID'], 'did'=>$orderdetails['Delivery_ID']], ['class'=>'raised-btn main-btn']); ?></td>
                        <?php endif;?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; } ?>
        </div>
    </div>
</div>