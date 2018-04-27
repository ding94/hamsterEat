<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;
use kartik\widgets\Select2;
use frontend\assets\MyOrdersAsset;
use frontend\controllers\CommonController;
$this->title = Yii::t('order','My Orders')." : ". Yii::t('order',$status);


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
                  'placeholder' => Yii::t('common','Go To ...'),
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
            <li><?php echo Html::a(Yii::t('common','All'),['/order/my-orders'])?></li>
            <?php foreach($countOrder as $i=> $count): ?>
              <li><?php echo Html::a(Yii::t('order',$i).'<span class="badge">'.$count['total'].'</span>',['/order/my-orders','status'=>$statusid[$i]])?></li>
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
              <th colspan="2"><center><?= Yii::t('common','More') ?></th>
              <th><center><?= Yii::t('common','Delivery ID') ?></th>
              <th><center><?= Yii::t('order','Date and Time Placed') ?></th>
            </tr>
          </thead>
          <?php foreach ($order as $data) :?>
          <tr class="orderRow">
            <td colspan="2" class="block" style="vertical-align: center;">
              <?php if($data['Orders_Status'] == 6 || $data['Orders_Status'] == 7): 
                  echo Html::a(Yii::t('order','Invoice Detail'),['invoice-pdf','did'=>$data['Delivery_ID']], ['target'=>'_blank' ,'class'=>'raised-btn main-btn']); 
                  if($data['Orders_Status'] == 6):
                    echo Html::a(Yii::t('rating','Rating'),['/rating/index','id'=>$data['Delivery_ID']], ['class'=>'raised-btn main-btn']);
                  endif;
              else :?>
                <a class="raised-btn main-btn" href="<?php echo yii\helpers\Url::to(['order-details','did'=>$data['Delivery_ID']]); ?>">
                  <i class="fa fa-info-circle"></i>
                </a>
              <?php endif ;?>
            </td>
            <td class="with" data-th="Delivery ID"  style="vertical-align: center;">
                <?php echo $data['Delivery_ID']; ?>
            </td>
            <?php $time = CommonController::getTime($data['Orders_DateTimeMade'],'Y-m-d h:i:s') ?>
            <td class="with" data-th="Date and Time Placed"  style="vertical-align: center;">
                <?= $time; ?>
            </td>

            <?php if ($data['Orders_Status'] == 1) : ?>
              <td  style="vertical-align: center;"> <?= Html::a(Yii::t('order','Go payment page'), ['/payment/default/process','did'=>$data['Delivery_ID']], ['class'=>'raised-btn main-btn']); ?></td> ?>
            <?php else: ?>
              <td  style="vertical-align: center;"><?= $label[$data['Orders_Status']]; ?></td>
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