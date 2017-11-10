<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use frontend\assets\MyOrdersAsset;
$this->title = "My Orders";

MyOrdersAsset::register($this);
?>
<div id="my-orders-container" class = "container">
  <div class="my-orders-header">
        <div class="my-orders-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
  <div class="content">
    <div class="col-sm-2 ">

      <ul id="my-orders-nav" class="nav nav-pills nav-stacked">
        <li class="active"><a data-toggle="pill" href="#pending">Pending<span class="badge"><?php echo Yii::$app->view->params['countPending'] ?></span></a></li>
        <li><a data-toggle="pill" href="#preparing">Preparing<span class="badge"><?php echo Yii::$app->view->params['countPreparing'] ?></span></a></li>
        <li><a data-toggle="pill" href="#pickup">Pick Up in Process<span class="badge"><?php echo Yii::$app->view->params['countPickup'] ?></span></a></li>
        <li><a data-toggle="pill" href="#ontheway">On The Way<span class="badge"><?php echo Yii::$app->view->params['countOntheway'] ?></span></a></li>
        <li><a data-toggle="pill" href="#completed">Completed<span class="badge"><?php echo Yii::$app->view->params['countCompleted'] ?></span></a></li>
      </ul>
    </div>
    <div class="col-sm-8 tab-content my-orders-table">
      <div id="pending" class="tab-pane fade in active">
        <?php if (empty($order1)){ ?>
        <div class ="order-icon">
         <?php echo Html::img('@web/imageLocation/Img/order-icon.png',['style'=>'width:100px; height:100px; margin-right:-13px; ']); ?>
          <p>No orders yet</p>
          </div>
        <?php } else{ ?>
        <table class="table table-user-info orderTable col-sm-8">
          <thead>
            <tr>
              <th><center>More</th>
              <th><center>Delivery ID</th>
              <th><center>Date and Time Placed</th>
            </tr>
          </thead>
          <?php 
            foreach ($order1 as $orders) :
              if($orders['Orders_Status'] == 'Pending')
              {
          ?>
          <tr class="orderRow">
            <td  class="block">
              <a class="btn btn-primary" href="<?php echo yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]); ?>">
                <i class="fa fa-info-circle"></i>
              </a>
            <td class="with" data-th="Delivery ID">
                <?php echo $orders['Delivery_ID']; ?>
            </td>
            <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
            <td class="with" data-th="Date and Time Placed">
                <?php echo date('Y-m-d h:i:s',$orders['Orders_DateTimeMade']); ?>
            </td>
          </tr>
          <?php  
              }
            endforeach;
          ?>
        </table>
        <?php }?>
      </div>
      <div id="preparing" class="tab-pane fade my-orders-table">
        <?php if (empty($order2)){ ?>
         <div class ="order-icon">
         <?php echo Html::img('@web/imageLocation/Img/order-icon.png',['style'=>'width:100px; height:100px; margin-right:-13px; ']); ?>
          <p>No orders yet</p>
          </div>
        <?php } else{ ?>
        <table class="table table-user-info orderTable col-sm-8">
          <thead>
            <tr>
              <th><center>More</th>
              <th><center>Delivery ID</th>
              <th><center>Date and Time Placed</th>
            </tr>
          </thead>
          <?php 
            foreach ($order2 as $orders) :
              if($orders['Orders_Status'] == 'Preparing')
              {
          ?>
          <tr class="orderRow">
            <td  class="block">
              <a class="btn btn-primary" href="<?php echo yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]); ?>">
                <i class="fa fa-info-circle"></i>
              </a>
            </td>
            <td class="with" data-th="Delivery ID">
                <?php echo $orders['Delivery_ID']; ?>
            </td>
            <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
            <td class="with" data-th="Date and Time Placed">
                <?php echo date('Y-m-d h:i:s',$orders['Orders_DateTimeMade']); ?>
            </td>
          </tr>
          <?php  
              }
            endforeach;
          ?>
        </table>
        <?php }?>
      </div>
      <div id="pickup" class="tab-pane fade my-orders-table">
        <?php if (empty($order3)){ ?>
           <div class ="order-icon">
         <?php echo Html::img('@web/imageLocation/Img/order-icon.png',['style'=>'width:100px; height:100px; margin-right:-13px; ']); ?>
          <p>No orders yet</p>
          </div>
        <?php } else{ ?>
        <table class="table table-user-info orderTable col-sm-8">
          <thead>
            <tr>
              <th><center>More</th>
              <th><center>Delivery ID</th>
              <th><center>Date and Time Placed</th>
            </tr>
          </thead>
          <?php 
            foreach ($order3 as $orders) :
              if($orders['Orders_Status'] == 'Pick Up in Process')
              {
          ?>
          <tr class="orderRow">
            <td  class="block">
              <a class="btn btn-primary" href="<?php echo yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]); ?>">
                <i class="fa fa-info-circle"></i>
              </a>
            </td>
            <td class="with" data-th="Delivery ID">
                <?php echo $orders['Delivery_ID']; ?>
            </td>
            <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
            <td class="with" data-th="Date and Time Placed">
                <?php echo date('Y-m-d h:i:s',$orders['Orders_DateTimeMade']); ?>
            </td>
          </tr>
          <?php  
              }
            endforeach;
          ?>
        </table>
        <?php }?>
      </div>
      <div id="ontheway" class="tab-pane fade my-orders-table">
        <?php if (empty($order4)){ ?>
           <div class ="order-icon">
         <?php echo Html::img('@web/imageLocation/Img/order-icon.png',['style'=>'width:100px; height:100px; margin-right:-13px; ']); ?>
          <p>No orders yet</p>
          </div>
        <?php } else{ ?>
        <table class="table table-user-info orderTable col-sm-8">
          <thead>
            <tr>
              <th><center>More</th>
              <th><center>Delivery ID</th>
              <th><center>Date and Time Placed</th>
            </tr>
          </thead>
          <?php 
            foreach ($order4 as $orders) :
              if($orders['Orders_Status'] == 'On The Way')
              {
          ?>
          <tr class="orderRow">
            <td  class="block">
              <a class="btn btn-primary" href="<?php echo yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]); ?>">
                <i class="fa fa-info-circle"></i>
              </a>
            </td>
            <td class="with" data-th="Delivery ID">
                <?php echo $orders['Delivery_ID']; ?>
            </td>
            <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
            <td class="with" data-th="Date and Time Placed">
                <?php echo date('Y-m-d h:i:s',$orders['Orders_DateTimeMade']); ?>
            </td>
          </tr>
          <?php  
              }
            endforeach;
          ?>
        </table>
        <?php }?>
      </div>
      <div id="completed" class="tab-pane fade my-orders-table">
        <table class="table table-user-info orderTable col-sm-8">
          <?php if (empty($order5)){ ?>
            <div class ="order-icon">
         <?php echo Html::img('@web/imageLocation/Img/order-icon.png',['style'=>'width:100px; height:100px; margin-right:-13px; ']); ?>
          <p>No orders yet</p>
          </div>
          <?php } else{ ?>
          <thead>
            <tr>
              <th><center>More</th>
              <th><center>Delivery ID</th>
              <th><center>Date and Time Placed</th>
              <th><center>Rate</th>
            </tr>
          </thead>
          <?php 
            foreach ($order5 as $orders) :
              if($orders['Orders_Status'] == 'Completed'|| $orders['Orders_Status']=='Rating Done')
              {
          ?>
          <tr class="orderRow">
            <td  class="block">
              <a class="btn btn-primary" href="<?php echo yii\helpers\Url::to(['order-details','did'=>$orders['Delivery_ID']]); ?>">
                <i class="fa fa-info-circle"></i>
              </a>
            </td>
            <td class="with" data-th="Delivery ID">
                <?php echo $orders['Delivery_ID']; ?>
            </td>
            <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
            <td class="with" data-th="Date and Time Placed">
                <?php echo date('Y-m-d h:i:s',$orders['Orders_DateTimeMade']); ?>
            </td>
            <?php if ($orders['Orders_Status'] != 'Completed'){ ?>
            <td><span class="rating-complete">Rating Done</span></td>
            <?php }else{ ?>
            <td class="block">
              <?php echo Html::a('Rate This Delivery', ['rating/index','id'=>$orders['Delivery_ID']], ['class'=>'btn btn-primary']); ?>
            </td>
            <?php } ?>
          </tr>
          <?php  
              }
            endforeach;
          ?>
        </table>
        <?php }?>
      </div>
    </div>
  </div>
</div>