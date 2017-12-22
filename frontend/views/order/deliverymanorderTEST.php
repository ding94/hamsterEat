<?php
/* @var $this yii\web\View */
use common\models\food\Food;
use common\models\Order\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use common\models\Order\Orders;
use common\models\Order\Orderitem;
use common\models\Restaurant;
use common\models\Company\Company;
use yii\helpers\Html;
use frontend\assets\DeliverymanOrdersAsset;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;

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
                    <li role="presentation" class="active"><?php echo Html::a("Deliveryman Orders",['order/deliveryman-orders'],['class'=>'btn-block'])?></li>
                    <li role="presentation"><?php echo Html::a("Deliveryman Orders History",['order/deliveryman-order-history'],['class'=>'btn-block'])?></li>
                    <li role="presentation"><?php echo Html::a("Delivery Location",['/Delivery/daily-sign-in/delivery-location'],['class'=>'btn-block'])?></li>
                </ul>
            </div>
        </div>
        <div id="deliveryman-orders-content" class="col-sm-10">
            <?php if (empty($data)): ?>
                <h3>You have no orders to deliver at the moment.</h3>
            <?php else :?>
               <?php foreach ($data as $rname => $restaurant) : ?>
				<?php  $address = array_shift($restaurant)?>
				<div style="border:1px solid black;">
					<div class="inner-row"><h4><b><?php echo $rname; ?></b></h4>
					<div style="padding-left:20px;"><?php echo Html::a('Show Location',"$address" ,['class'=>'raised-btn main-btn','target'=>'_blank']);?>
				</div>
				</div>
				<?php foreach($restaurant as $sname=> $order): ?>
				<?php  $totalOrders = count($order)?>
				
				<div id="parent">
    <div class="block left">
	<a data-toggle="collapse" data-target="#<?php echo $sname; ?>"><i class="fa fa-plus-circle fa-lg" aria-hidden="true" style="color:#ffda00;padding-right:10px;"></i></a><?php echo $sname; ?>
	 
	</div>
    <div  class="block center">
	 Quantity: <?php echo $totalOrders; ?>
	</div>
    <div class="block right">
	 <?php echo Html::a('Picked Up' ,"", ['class'=>'raised-btn main-btn']); ?>
	 </div>
	

				
				
				
					<!--<div class="dm" >
						<div class="outer-row"> 
							<div class="inner-row">
								<div class="dm-text"> <a data-toggle="collapse" data-target="#<?php echo $sname; ?>"><i class="fa fa-plus-circle fa-lg" aria-hidden="true" style="color:#ffda00;padding-right:10px;"></i></a><?php echo $sname; ?></div>
								
								<div class="dm-text">Quantity: <?php echo $totalOrders; ?></div>
									<div class="dm-status"> <?php echo Html::a('Picked Up' ,"", ['class'=>'raised-btn main-btn']); ?></div>
								
								
							</div>
						</div>-->
						<div id=<?= $sname; ?>  class="collapse">
						Order ID: <?php foreach ($order as $sname => $id ): ?>
						<div style="display:inline;"><?php echo $id; ?> <div style="display:inline;color:#ffda00;">&nbsp;|</div></div>
				<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			 <?php endif ;?>
		</div>
        </div>
    </div>
</div>