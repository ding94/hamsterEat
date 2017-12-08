<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use kartik\widgets\Select2;
use yii\helpers\Html;
use frontend\assets\RestaurantOrdersAsset;
use yii\widgets\LinkPager;

$this->title = $restaurantname['Restaurant_Name']."'s Orders";
RestaurantOrdersAsset::register($this);
?>

<div id="restaurant-orders-container" class = "container">
    <div class="restaurant-orders-header">
        <div class="restaurant-orders-header-title"><?= Html::encode($this->title) ?></div>
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
                <ul id="restaurant-orders-nav" class="nav nav-pills nav-stacked">
                    <li><?php echo Html::a("<i class='fa fa-chevron-left'></i> Back",['Restaurant/default/manage-restaurant-staff', 'rid'=>$rid])?></li>
                    <?php foreach($countOrder as $i=> $count):?>
                      <li><?php echo Html::a($i.'<span class="badge">'.$count['total'].'</span>',['/order/restaurant-orders','status'=>$i,'rid'=>$rid])?></li>
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="restaurant-orders-content" class="col-sm-10">
        <?php
            if (empty($result)) { ?>
                <h2>There are no orders currently...</h2>
            <?php }else { ?>
            <div class = "switchbutton"> <?php
                if ($mode == 1)
                {
                    echo Html::a('View Nicknames', ['restaurant-orders', 'rid'=>$rid, 'status'=>$status, 'mode'=>2], ['class'=>'raised-btn btn-default fa fa-exchange swap-button']);
                }
                else
                {
                    echo Html::a('View Food Names', ['restaurant-orders', 'rid'=>$rid, 'status'=>$status, 'mode'=>1], ['class'=>'raised-btn btn-default fa fa-exchange swap-button']);
                } ?>
            </div> <?php
            foreach ($result as $k => $results) : ?>
            
            <?php if($k != 0): ?> <!--- if not first array -->
                <?php if($results['Delivery_ID'] != $result[$k - 1 ]['Delivery_ID']) : ?> <!---while delivery id not same, give header -->
                    <table class="table table-hover" style="border:1px solid black;"> 
                        <thead>
                            <tr>
                                <th colspan = '6' data-th="Delivery_ID" ><center>Delivery ID: <?php echo $results['Delivery_ID']; ?></th>
                            </tr>
                        </thead>
                        <thead class='none'>
                            <tr>
                                <th> Order ID </th>
                                <th><?php echo $mode == 1 ? 'Food Name' : 'Nick Name' ?></th>
                                <th> Selections </th>
                                <th> Quantity </th>
                                <th> Remarks </th>
                                <th> Update Status </th>
                            </tr>
                        </thead>
                    <?php endif; ?>
            <?php else: ?> <!--- first array, give header -->
                    <table class="table table-hover" style="border:1px solid black;"> 
                    <thead>
                        <tr>
                            <th colspan = '6' data-th="Delivery_ID" ><center>Delivery ID: <?php echo $results['Delivery_ID']; ?></th>
                        </tr>
                    </thead>
                    <thead class='none'>
                        <tr>
                            <th> Order ID </th>
                            <th><?php echo $mode == 1 ? 'Food Name' : 'Nick Name' ?></th>
                            <th> Selections </th>
                            <th> Quantity </th>
                            <th> Remarks </th>
                            <th> Update Status </th>
                        </tr>
                    </thead>
            <?php endif; ?>

                        <tr>
                            <td data-th="Order ID"><?php echo $results['Order_ID']; ?></td>
                            <td data-th="Food Name"><?php echo $mode == 1 ? $results['food']['Name'] : $results['food']['Nickname'] ?></td>

                            <?php 
                            $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$results['Order_ID']])->all(); ?>
                            <td data-th="Selections">
                            <?php foreach ($selections as $selections) :
                                $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
                                $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
                                if (!is_null($selectionname['ID']))
                                { 
                                    $name = $mode == 1 ? $selectionname['Name'] : $selectionname['Nickname'];
                                    echo $selectiontype['TypeName'].': &nbsp;'.$name;
                                    echo "<br>";
                                }
                            endforeach; ?>
                            </td>
                            <td data-th="Quantity"><?php echo $results['OrderItem_Quantity']; ?></td>
                            <td data-th="Remarks"><?php echo $results['OrderItem_Remark']; ?></td>

                            <?php if ($results['OrderItem_Status'] == 'Pending'): ?>
                                <td data-th="Update Status"><?php echo Html::a('Preparing', ['update-preparing', 'oid'=>$results['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn']); ?></td>

                            <?php elseif ($results['OrderItem_Status'] == 'Preparing'): ?>
                                <td data-th="Update Status"><?php echo Html::a('Ready for Pick Up', ['update-readyforpickup', 'oid'=>$results['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn']); ?></td>

                            <?php elseif ($results['OrderItem_Status'] == 'Ready For Pick Up'): ?>
                                <td data-th="Update Status"><span class='label label-warning'> Waiting for Pick Up </span></td>

                            <?php elseif ($results['OrderItem_Status'] == 'Picked Up'): ?>
                                <td data-th="Update Status"><span class='label label-warning'> Picked Up </span></td>

                            <?php elseif ($results['OrderItem_Status'] == 'Canceled'): ?>
                                <td data-th="Update Status"><span class='label label-danger'> Canceled </span></td>

                            <?php elseif ($results['OrderItem_Status'] == 'Canceled and Refunded'): ?>
                                <td data-th="Update Status"><span class='label label-danger'> Canceled & Refunded </span></td>

                            <?php endif; ?>
                        </tr>

                    <?php if ($k !=0) : ?> <!--- aware from error of -1 array -->

                        <?php if($k < count($result)-1):  ?> <!--- continue this if, while haven't reached max array count -->

                            <?php if ($results['Delivery_ID'] == $result[$k + 1 ]['Delivery_ID']) : ?> <!-- if id was same as next, no close table-->

                            <?php elseif ($results['Delivery_ID'] != $result[$k - 1 ]['Delivery_ID']) : ?> <!--- not same with previous, close table -->
                                </table>
                            <?php endif; ?> <!--- 3rd if end-->

                        <?php else : ?> <!--- 2nd if, stop if while reached max array count-->
                            </table>
                        <?php endif; ?> <!--- 2nd if end-->

                    <?php endif; ?> <!--- 1st if end-->

            <?php endforeach; } ?>

            </table>

            <?php echo LinkPager::widget([
                  'pagination' => $pagination,
                  ]); ?>
        </div>
    </div>
</div>