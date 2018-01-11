<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\widgets\Select2;
use frontend\assets\RestaurantOrdersHistoryAsset;
use yii\widgets\LinkPager;

$this->title = $title;
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
                <?php foreach ($result as $delivery) :?>  
                <table class="table table-user-info table-hover" style="border:1px solid black;">
                    <thead>
                        <tr>
                            <th colspan = '3' data-th="Delivery_ID">
                                <center>Delivery ID: <?= $delivery->Delivery_ID?> 
                            </th>
                            <th colspan= '2' data-th="Delivery Status">
                                <center>Status: <?= $statusid[$delivery->Orders_Status] ?>
                            </th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th><center> Order ID </th>
                            <th><center> Food Name </th>
                            <th><center> Selection </th>
                            <th><center> Quantity </th>
                            <th><center> Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($delivery['item'] as $order):?>
                        <tr>
                            <td data-th="Order ID"><?= $order->Order_ID?></td>
                            <td data-th="Food Name"><?= $order->food->Name?></td>
                        </tr>
                        <?php endforeach ;?>   
                    </tbody>
                </table>
                <?php endforeach;?>
        <?php endif ;?>
        <?php echo LinkPager::widget([
          'pagination' => $pagination,
          ]); ?>
        </div>
    </div>
</div>