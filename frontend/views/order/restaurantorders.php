<?php
/* @var $this yii\web\View */

use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Company\Company;
use kartik\widgets\Select2;
use yii\helpers\Html;
use frontend\assets\RestaurantOrdersAsset;
use yii\widgets\LinkPager;
use kartik\widgets\ActiveForm;

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
                    <li><?php echo Html::a("<i class='fa fa-chevron-left'></i> Back",['order/restaurant-order-history', 'rid'=>$rid])?></li>
                    <li><?php echo Html::a("All",['order/restaurant-orders', 'rid'=>$rid])?></li>
                    <?php foreach($countOrder as $i=> $count):?>
                      <li><?php echo Html::a($i.'<span class="badge">'.$count['total'].'</span>',['/order/restaurant-orders','status'=>$statusid[$i],'rid'=>$rid])?></li>
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="restaurant-orders-content" class="col-sm-10">
        <div><?php echo Html::a('Cooking Detail',['/Restaurant/restaurant/cooking-detail','rid'=>$rid],['class'=>'btn btn-default','style'=>'margin-bottom:20px;','target'=>'_blank']) ?>
        <?php if (empty($companyData)) :?>
            <h2>There are no orders currently...</h2>
        <?php else :?>
            <div class = "switchbutton"> 
                <?php
                    if ($mode == 1)
                    {
                        echo Html::a('View Nicknames', ['restaurant-orders', 'rid'=>$rid, 'status'=>$status, 'mode'=>2], ['class'=>'raised-btn btn-default fa fa-exchange swap-button']);
                    }
                    else
                    {
                        echo Html::a('View Food Names', ['restaurant-orders', 'rid'=>$rid, 'status'=>$status, 'mode'=>1], ['class'=>'raised-btn btn-default fa fa-exchange swap-button']);
                    } 
                ?>
            </div> 
             
            <?php foreach($companyData as $name => $delivery): ?>
                <div class="outer-div" style="border:1px solid black;">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if($status == 2 || $status == 3):?>
                                <?php $form = ActiveForm::begin(['method'=>'post','action'=>['/Restaurant/restaurantorder/mutiple-order','status'=>$status,'rid'=>$rid]])?>
                                <h2><center><?php echo Html::checkbox('null',false ,['class'=>'check-all','id'=>'order']) ?></center></h2>
                            <?php endif ;?>
                        </div>
                        <div class="col-md-4">
                            <h2><center><?= $name; ?></center> </h2> 
                        </div>
                        <div class="col-md-4">
                            <?php 
                                if($status == 2 || $status == 3): 
                                $name = $status == 2 ? 'Preparing' : 'Ready Pick Up';
                            ?>

                                <h2><center><?= Html::submitButton($name, ['class' => 'raised-btn main-btn']) ?></center></h2>
                            <?php endif ;?>
                        </div>
                    </div> 
                <table class="table table-hover" style="border:0px solid black;">
                <?php foreach($delivery as $deliveyid => $deliveryitems) :?>
                  
                        <thead>
                            <tr>
                                <th colspan = '6' data-th="Delivery_ID" style="background-color:#fffced;"><center>Delivery ID: <?= $deliveyid; ?> </th>
                            </tr>
                        </thead>
                        <thead class='none'>
                            <tr>
                                <th>Order ID</th>
                                <th><?php echo $mode == 1 ? 'Food Name' : 'Nick Name' ?></th>
                                <th> Selections </th>
                                <th> Quantity </th>
                            <!--    <th> Remarks </th>-->
                                <th> Update Status </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($deliveryitems as $key => $orderitem): ?>
                            <tr>
                                
                                <td data-th="Order ID">
                                    <?php 
                                        if($status == 2 || $orderitem['OrderItem_Status'] == 3):
                                            echo Html::checkbox('oid[]',false ,['label'=>$orderitem['Order_ID'] ,'value'=> $orderitem['Order_ID']]);
                                        else :
                                            echo $orderitem['Order_ID']; 
                                        endif ;
                                    ?>
                                </td>
                                <td data-th="Food Name"><?php echo $mode == 1 ? $orderitem['food']['Name'] : $orderitem['food']['Nickname'] ?></td>
                                <?php $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$orderitem['Order_ID']])->all(); ?>
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
                                <td data-th="Quantity"><?= $orderitem['OrderItem_Quantity']; ?></td>
                            <!--    <td data-th="Remark"><?= $orderitem['OrderItem_Remark']; ?></td>-->
                                <?php if ($orderitem['OrderItem_Status'] == 2): ?>
                                    <td data-th="Update Status"><?php echo Html::a('Preparing', ['update-preparing', 'oid'=>$orderitem['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn']); ?></td>

                                <?php elseif ($orderitem['OrderItem_Status'] == 3): ?>
                                    <td data-th="Update Status"><?php echo Html::a('Ready for Pick Up', ['update-readyforpickup', 'oid'=>$orderitem['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn']); ?></td>

                                <?php elseif ($orderitem['OrderItem_Status'] == 4): ?>
                                    <td data-th="Update Status"><span class='label label-warning'> Waiting for Pick Up </span></td>

                                <?php elseif ($orderitem['OrderItem_Status'] == 10): ?>
                                    <td data-th="Update Status"><span class='label label-warning'> Picked Up </span></td>

                                <?php elseif ($orderitem['OrderItem_Status'] == 8): ?>
                                    <td data-th="Update Status"><span class='label label-danger'> Canceled </span></td>

                                <?php elseif ($orderitem['OrderItem_Status'] == 9): ?>
                                    <td data-th="Update Status"><span class='label label-danger'> Canceled & Refunded </span></td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    
                <?php endforeach; ?>
                </table>
                    <?php if($status == 2 || $status == 3):?>
                        <?php ActiveForm::end(); ?>
                    <?php endif ;?>
                </div>
            <?php endforeach; ?>
           
            <?php echo LinkPager::widget([
                  'pagination' => $pagination,
                  ]); ?>
        <?php endif ;?>
        </div>
    </div>
</div>