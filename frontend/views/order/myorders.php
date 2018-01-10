<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\widgets\Select2;
use frontend\assets\MyOrdersAsset;

$this->title = "My Orders : ". $status;


MyOrdersAsset::register($this);
?>
<div class="order">
<div id="my-orders-container" class = "container">
  <div class="my-orders-header">
      <div class="my-orders-header-title"><?php echo Html::encode($this->title)?></div>
  </div>
  <div class="content">
    <div class="col-sm-2">
        <div class="dropdown-url">
          <?php echo Select2::widget([
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
          ]);?>
        </div>
        <div class="nav-url">
          <ul id="my-orders-nav" class="nav nav-pills nav-stacked">
            <li><?php echo Html::a("All",['/order/my-orders'])?></li>
            <?php foreach($countOrder as $i=> $count): ?>
              <li><?php echo Html::a($i.'<span class="badge">'.$count['total'].'</span>',['/order/my-orders','status'=>$statusid[$i]])?></li>
            <?php endforeach ;?>
        </ul>
      </div>
    </div>
    <div class="col-sm-10 tab-content my-orders-content">
      <div id="pending" class="tab-pane fade my-orders-table in active">
        <?php if (empty($order)) : ?>
        <div class ="order-icon">
         <?php echo Html::img('@web/imageLocation/Img/order-icon.png',['class' =>'empty-img']); ?>
          <p>No orders yet</p>
        </div>
        <?php  else : ?>
        <table class="table table-user-info orderTable">
          <thead>
            <tr>
              <th colspan="2"><center>More</th>
              <th><center>Delivery ID</th>
              <th><center>Date and Time Placed</th>
            </tr>
          </thead>
          <?php foreach ($order as $data) :?>
          <tr class="orderRow">
            <td colspan="2" class="block">
              <?php if($data['Orders_Status'] == 6 || $data['Orders_Status'] == 7): ?>
                 <?php echo Html::a("Invoice Detail" ,['invoice-pdf','did'=>$data['Delivery_ID']], ['target'=>'_blank' ,'class'=>'raised-btn main-btn btn-block']); ?>
              <?php else :?>
                <a class="raised-btn main-btn btn-block" href="<?php echo yii\helpers\Url::to(['order-details','did'=>$data['Delivery_ID']]); ?>">
                  <i class="fa fa-info-circle"></i>
                </a>
              <?php endif ;?>
            </td>
            <td class="with" data-th="Delivery ID">
                <?php echo $data['Delivery_ID']; ?>
            </td>
            <?php date_default_timezone_set("Asia/Kuala_Lumpur"); ?>
            <td class="with" data-th="Date and Time Placed">
                <?php echo date('Y-m-d h:i:s',$data['Orders_DateTimeMade']); ?>
            </td>

            <?php if ($data['Orders_Status'] == 1) : ?>
              <td> <?= Html::a('Go payment page', ['payment/process-payment','did'=>$data['Delivery_ID']], ['class'=>'raised-btn main-btn']); ?></td> ?>
            <?php elseif ($data['Orders_Status'] == 7) : ?>
              <td><center> Rating Done </td>
            <?php else: ?>
              <td><?= $label[$data['Orders_Status']]; ?></td>
            <?php endif;?>

          </tr>
          <?php endforeach;?>
        </table>
        <?php endif ;?>
      </div>
      <?php echo LinkPager::widget([
          'pagination' => $pagination,
    ]); ?>
    </div>
  </div>
</div>
</div>