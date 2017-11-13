<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Orders;
use common\models\Orderitem;
use yii\helpers\Html;
use kartik\widgets\Select2;
use frontend\controllers\CartController;
use frontend\assets\RestaurantOrdersHistoryAsset;

$this->title = "Restaurant Orders History";
RestaurantOrdersHistoryAsset::register($this);
?>
<div id="restaurant-orders-history-container" class = "container">
    <div class="restaurant-orders-history-header">
        <div class="restaurant-orders-history-header-title"><?= Html::encode($this->title) ?></div>
    </div>
	<a href="#top" class="scrollToTop"></a>
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
                <ul id="restaurant-orders-history-nav" class="nav nav-pills nav-stacked">
                    <?php if ($staff['RmanagerLevel_Level'] == 'Owner'){ ?>
                        <li role="presentation"><?php echo Html::a("View Earnings",['Restaurant/default/show-monthly-earnings', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php }
                    if ($staff['RmanagerLevel_Level'] == 'Owner' || $staff['RmanagerLevel_Level'] == 'Manager') { ?>
                        <li role="presentation"><?php echo Html::a("Edit Details",['Restaurant/default/edit-restaurant-details', 'rid'=>$rid, 'restArea'=>$restaurantname['Restaurant_AreaGroup'], 'areachosen'=>$restaurantname['Restaurant_Area'], 'postcodechosen'=>$restaurantname['Restaurant_Postcode']],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Staffs",['Restaurant/default/manage-restaurant-staff', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation" class="active"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation"><?php echo Html::a("Manage Menu",['/food/menu', 'rid'=>$rid,'page'=>'menu'],['class'=>'btn-block'])?></li>
                    <?php } elseif ($staff['RmanagerLevel_Level'] == 'Operator'){ ?>
                        <li role="presentation"><?php echo Html::a("Restaurants Orders",['/order/restaurant-orders', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                        <li role="presentation" class="active"><?php echo Html::a("Restaurants Orders History",['/order/restaurant-order-history', 'rid'=>$rid],['class'=>'btn-block'])?></li>
                    <?php } ?>
                </ul>
            </div>
            
        </div>
        <div id="restaurant-orders-history-content" class="col-sm-10">
            <?php if(empty($result)) :?>
                 <h2>There are no orders currently...</h2>
            <?php else :?>
            <?php 
            foreach ($result as $result) :
            ?>  
                <table class="table table-user-info table-hover" style="border:1px solid black;">
                    <thead>
                        <tr>
                            <th><center> Delivery ID </th>
                            <th><center> Username </th>
                            <th><center> Date to be Received </th>
                            <th><center> Time to be Received </th>
                            <th><center> Current Status </th>
                            <th colspan="2"><center> Time Placed </th>
                            <th><center> Money Collected (RM) </th>
                        </tr>
                    </thead>
                    <?php
                    $orderdetails = Orders::find()->where('Delivery_ID = :did', [':did'=>$result['Delivery_ID']])->one();

                    if($orderdetails['Orders_Status']== 'Rating Done')
                    {
                        $label='<span class="label label-success">'.$orderdetails['Orders_Status'].'</span>';
                    }
                    // This calculates the total amount the restaurant earns from this whole order (For each specific restaurant)
                    $orderids = Orderitem::find()->where('Delivery_ID = :did and food.Restaurant_ID = :rid', [':did'=>$orderdetails['Delivery_ID'], ':rid'=>$restaurantname['Restaurant_ID']])->joinWith('food.restaurant')->all();
                    $thefinalselectionprice = 0;
                    $thefinalmoneycollected = 0;
                    $thefinalfoodprice = 0;
                    $themoneycollected = 0;
                    foreach ($orderids as $orderids) :
                        $thequantity = $orderids['OrderItem_Quantity'];
                        $theorderid = $orderids['Order_ID'];
                        $thefoodid = $orderids['Food_ID'];

                        $thefoodprice = Food::find()->where('Food_ID = :fid', [':fid' => $thefoodid])->one();
                        $thefoodprice = $thefoodprice['BeforeMarkedUp'];

                        $selectionids = Orderitemselection::find()->where('Order_ID = :oid', [':oid' => $theorderid])->all();
                        foreach ($selectionids as $selectionids) :
                            $theselectionid = $selectionids['Selection_ID'];

                            $theselectionprice = Foodselection::find()->where('ID = :sid', [':sid' => $theselectionid])->one();
                            $theselectionprice = $theselectionprice['BeforeMarkedUp'];
                            
                            $thefinalselectionprice = $thefinalselectionprice + $theselectionprice;
                        endforeach;
                        
                        $thefinalfoodprice = $thefinalfoodprice + $thefoodprice;
                        $themoneycollected = ($thefinalfoodprice + $thefinalselectionprice) * $thequantity;
                    endforeach;
                    $thefinalmoneycollected = $thefinalmoneycollected + $themoneycollected;
                    ?>
                    <tr>
                        <td data-th="Delivery ID"><?php echo $orderdetails['Delivery_ID']; ?></td>
                        <td data-th="Username"><?php echo $orderdetails['User_Username']; ?></td>
                        <td data-th="Date to be Received"><?php echo $orderdetails['Orders_Date']; ?></td>
                        <td data-th="Time to be Received"><?php echo $orderdetails['Orders_Time']; ?></td>
                        <td data-th="Current Status"><?php echo $label; ?></td>
                        <?php date_default_timezone_set("Asia/Kuala_Lumpur");
                        $timeplaced = date('d/m/Y H:i:s', $orderdetails['Orders_DateTimeMade']); ?>
                        <td colspan ="2" data-th="Time Placed"><?php echo $timeplaced; ?></td>
                        <td data-th="Money Collected (RM)"><?php echo CartController::actionRoundoff1decimal($thefinalmoneycollected); ?></td>
                    </tr>
                    <thead>
                        <tr>
                            <th><center> Order ID </th>
                            <th><center> Food Name </th>
                            <th><center> Selections </th>
                            <th><center> Quantity </th>
                            <th><center> Remarks </th>
                            <th colspan="2"><center> Current Status </th>
                            <th><center> Order Earnings (RM) </th>
                        </tr>
                    </thead>
                    <?php 
                    $orderitemdetails = "SELECT * from orderitem INNER JOIN food ON orderitem.Food_ID = food.Food_ID INNER JOIN restaurant on restaurant.Restaurant_ID = food.Restaurant_ID WHERE food.Restaurant_ID = ".$restaurantname['Restaurant_ID']." AND orderitem.Delivery_ID = ".$orderdetails['Delivery_ID']."";
                    $resultz = Yii::$app->db->createCommand($orderitemdetails)->queryAll();
                    //var_dump($resultz);exit;
                    foreach ($resultz as $orderitemdetails) :
                        ?>
                        <tr>
                            <td data-th="Order ID"><?php echo $orderitemdetails['Order_ID']; ?></td>
                            <?php $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$orderitemdetails['Food_ID']])->one(); ?>
                            <td data-th="Food Name"><?php echo $foodname['Name']; ?></td>
                            <?php $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$orderitemdetails['Order_ID']])->all(); ?>
                            <td data-th="Selections">
                            <?php foreach ($selections as $selections) :

                                $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
                                $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
                                if (!is_null($selectionname['ID']))
                                {
                                    echo $selectiontype['TypeName'].': &nbsp;'.$selectionname['Name'];
                                    ?>
                                    <br>
                                    <?php 
                                    $foodselectionprice;
                                }
                            endforeach;
                            ?>
                            </td>

                            <td data-th="Quantity"><?php echo $orderitemdetails['OrderItem_Quantity']; ?></td>
                            <td data-th="Remarks"><?php echo $orderitemdetails['OrderItem_Remark']; ?></td>
                            <td colspan="2" data-th="Current Status"><span class='label label-info'><?php echo $orderitemdetails['OrderItem_Status']; ?></span></td>
                            <?php 
                            if ($orderitemdetails['OrderItem_Status'] == 'Pending')
                            {
                                echo "<td colspan = 2 data-th='Current Status'>".Html::a('Preparing', ['update-preparing', 'oid'=>$orderitemdetails['Order_ID'], 'rid'=>$rid], ['class'=>'btn btn-primary'])."</td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Preparing')
                            {
                                echo "<td colspan = 2 data-th='Current Status'>".Html::a('Ready for Pick Up', ['update-readyforpickup', 'oid'=>$orderitemdetails['Order_ID'], 'rid'=>$rid], ['class'=>'btn btn-primary'])."</td>";
                            }
                            elseif ($orderitemdetails['OrderItem_Status'] == 'Ready For Pick Up')
                            {
                                echo "<td colspan = 2 data-th='Current Status'> Waiting for Pick Up </td>";
                            }
                            ?>

                            <td data-th="Order Earnings (RM)"><?php echo CartController::actionRoundoff1decimal($orderitemdetails['Restaurant_Share']); ?></td>
                        </tr>
                    <?php 
                    endforeach;
                    ?>
                </table>
                <br>
                <br>
            </center>
        <?php
            endforeach;
        ?>
        <?php endif ;?>
        </div>
    </div>
</div>