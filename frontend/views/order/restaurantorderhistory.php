<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use yii\helpers\Html;
use kartik\widgets\Select2;
use frontend\controllers\CartController;
use frontend\assets\RestaurantOrdersHistoryAsset;
use yii\widgets\LinkPager;

$this->title = $restaurantname['Restaurant_Name']."'s Orders History";
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
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="Restaurant Orders History" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                        </li>   
                    <?php endforeach ;?>
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
                    // This calculates the total amount the restaurant earns from this whole order (For each specific restaurant)
                    $thefinalselectionprice = 0;
                    $thefinalmoneycollected = 0;
                    $thefinalfoodprice = 0;
                    $themoneycollected = 0;
                    $thequantity = $result['OrderItem_Quantity'];
                    $theorderid = $result['Order_ID'];
                    $thefoodid = $result['Food_ID'];

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
                    $thefinalmoneycollected = $thefinalmoneycollected + $themoneycollected;
                    ?>
                    <tr>
                        <td data-th="Delivery ID"><?php echo $result['Delivery_ID']; ?></td>
                        <td data-th="Username"><?php echo $result['order']['User_Username']; ?></td>
                        <td data-th="Date to be Received"><?php echo $result['order']['Orders_Date']; ?></td>
                        <td data-th="Time to be Received"><?php echo $result['order']['Orders_Time']; ?></td>
                        <td data-th="Current Status"><span class="label label-success"><?php echo $result['order']['Orders_Status']; ?></span></td>
                        <?php date_default_timezone_set("Asia/Kuala_Lumpur");
                        $timeplaced = date('d/m/Y H:i:s', $result['order']['Orders_DateTimeMade']); ?>
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
                        <tr>
                            <td data-th="Order ID"><?php echo $result['Order_ID']; ?></td>
                            <?php $foodname = Food::find()->where('Food_ID = :fid', [':fid'=>$result['Food_ID']])->one(); ?>
                            <td data-th="Food Name"><?php echo $foodname['Name']; ?></td>
                            <?php $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$result['Order_ID']])->all(); ?>
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
                            <td data-th="Quantity"><?php echo $result['OrderItem_Quantity']; ?></td>
                            <td data-th="Remarks"><?php echo $result['OrderItem_Remark']; ?></td>
                            <td colspan="2" data-th="Current Status"><span class='label label-info'><?php echo $result['OrderItem_Status']; ?></span></td>
                            <?php 
                            if ($result['OrderItem_Status'] == 'Pending')
                            {
                                echo "<td colspan = 2 data-th='Current Status'>".Html::a('Preparing', ['update-preparing', 'oid'=>$result['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn'])."</td>";
                            }
                            elseif ($result['OrderItem_Status'] == 'Preparing')
                            {
                                echo "<td colspan = 2 data-th='Current Status'>".Html::a('Ready for Pick Up', ['update-readyforpickup', 'oid'=>$result['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn'])."</td>";
                            }
                            elseif ($result['OrderItem_Status'] == 'Ready For Pick Up')
                            {
                                echo "<td colspan = 2 data-th='Current Status'> Waiting for Pick Up </td>";
                            }
                            ?>

                            <td data-th="Order Earnings (RM)"><?php echo CartController::actionRoundoff1decimal($result['Restaurant_Share']); ?></td>
                        </tr>
                </table>
                <br>
                <br>
            </center>
        <?php
            endforeach;
        ?>
        <?php endif ;?>
        <?php echo LinkPager::widget([
          'pagination' => $pagination,
          ]); ?>
        </div>
    </div>
</div>