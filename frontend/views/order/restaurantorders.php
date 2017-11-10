<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use common\models\Orderitem;
use kartik\widgets\Select2;
use yii\helpers\Html;
use frontend\assets\RestaurantOrdersAsset;

$this->title = "Restaurant Orders";
RestaurantOrdersAsset::register($this);
?>
<div id="restaurant-orders-container" class = "container">
    <div class="restaurant-orders-header">
        <div class="restaurant-orders-header-title"><?= Html::encode($this->title) ?></div>
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
                <ul id="restaurant-orders-nav" class="nav nav-pills nav-stacked">
                    <?php if ($staff['RmanagerLevel_Level'] == 'Owner'){ ?>
                        <li role="presentation"><?php echo Html::a("View Earnings",['Restaurant/default/show-monthly-earnings', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php }
                    if ($staff['RmanagerLevel_Level'] == 'Owner' || $staff['RmanagerLevel_Level'] == 'Manager') { ?>
                        <li role="presentation"><?php echo Html::a("Edit Details",['Restaurant/default/edit-restaurant-details', 'rid'=>$rid, 'restArea'=>$restaurantname['Restaurant_AreaGroup'], 'areachosen'=>$restaurantname['Restaurant_Area'], 'postcodechosen'=>$restaurantname['Restaurant_Postcode']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Staffs",['Restaurant/default/manage-restaurant-staff', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation" class="active"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Menu",['/food/menu', 'rid'=>$rid,'page'=>'menu'],['class'=>'btn-block'])?></li>
                    <?php } elseif ($staff['RmanagerLevel_Level'] == 'Operator'){ ?>
                        <li role="presentation" class="active"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div id="restaurant-orders-content" class="col-sm-10">
        <?php
            if (empty($result)) { ?>
                <h2>There are no orders currently...</h2>
            <?php }else {
            foreach ($result as $result) : ?>
                    <?php $orderdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$result['Delivery_ID']])->one();?>
                    <?php if($orderdetails['Orders_Status'] == 'Pending' || $orderdetails['Orders_Status'] == 'Preparing' || $orderdetails['Orders_Status'] == 'Ready For Pick Up'): ?>
                <table class="table table-hover" style="border:1px solid black;">  
                    <thead>
                        <tr>
                            <th colspan = '6' data-th="Delivery_ID" ><center>Delivery ID: <?php echo $orderdetails['Delivery_ID']; ?></th>
                        </tr>
                    </thead>
                    <thead class='none'>
                        <tr>
                            <th> Order ID </th>
                            <th> Food Name </th>
                            <th> Selections </th>
                            <th> Quantity </th>
                            <th> Remarks </th>
                            <th> Update Status </th>
                        </tr>
                    </thead>

                    <?php 
                    $orderitemdetails = "SELECT * from orderitem INNER JOIN food ON orderitem.Food_ID = food.Food_ID INNER JOIN restaurant on restaurant.Restaurant_ID = food.Restaurant_ID WHERE food.Restaurant_ID = ".$restaurantname['Restaurant_ID']." AND orderitem.Delivery_ID = ".$orderdetails['Delivery_ID']."";
                    $resultz = Yii::$app->db->createCommand($orderitemdetails)->queryAll();
                    foreach ($resultz as $orderitemdetails) : ?>
                    <?php if ($orderitemdetails['OrderItem_Status'] == 'Pending' || $orderitemdetails['OrderItem_Status'] == 'Preparing' || $orderitemdetails['OrderItem_Status'] == 'Ready For Pick Up'): ?>
                        <tr>
                            <td data-th="Order ID"><?php echo $orderitemdetails['Order_ID']; ?></td>
                            <?php 
                            $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one(); ?>
                            <td data-th="Food Name"><?php echo $foodname['Name']; ?></td>
                            <?php 
                            $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$orderitemdetails['Order_ID']])->all(); ?>
                            <td data-th="Selections">
                            <?php foreach ($selections as $selections) :
                                $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
                                $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
                                if (!is_null($selectionname['ID']))
                                { 
                                    echo $selectiontype['TypeName'].': &nbsp;'.$selectionname['Name'];
                                    echo "<br>";
                                }
                            endforeach; ?>
                            </td>
                            <td data-th="Quantity"><?php echo $orderitemdetails['OrderItem_Quantity']; ?></td>
                            <td data-th="Remarks"><?php echo $orderitemdetails['OrderItem_Remark']; ?></td>
                            <?php 
                            if ($orderitemdetails['OrderItem_Status'] == 'Pending')
                            { ?>
                                <td data-th="Update Status"><?php echo Html::a('Preparing', ['update-preparing', 'oid'=>$orderitemdetails['Order_ID'], 'rid'=>$rid], ['class'=>'btn btn-primary']); ?></td>
                            <?php }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Preparing')
                            { ?>
                                <td data-th="Update Status"><?php echo Html::a('Ready for Pick Up', ['update-readyforpickup', 'oid'=>$orderitemdetails['Order_ID'], 'rid'=>$rid], ['class'=>'btn btn-primary']); ?></td>
                            <?php }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Ready For Pick Up')
                            { ?>
                                <td data-th="Update Status"><span class='label label-warning'> Waiting for Pick Up </span></td>
                            <?php } ?>
                        </tr>
                    <?php endif ?>
                    <?php endforeach; ?>
                </table>
                <br>
                <br>
                <?php endif ?>
            <?php endforeach; 
            } ?>
        </div>
    </div>
</div>