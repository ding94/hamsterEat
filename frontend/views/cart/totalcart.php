<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\controllers\CartController;
use yii\helpers\Url;
use frontend\assets\CartAsset;

CartAsset::register($this);
?>
<div class="container">
    <div class="col-md-3 col-md-offset-2" id='voucher'>
      <?php $url = Url::to(['cart/getdiscount']);
            echo Html::hiddenInput('dis-url',$url);
      ?>			   
      <?php if (!empty($voucher)): ?>
        <?php $form = ActiveForm::begin(); ?>
        <div>
            <?= $form->field($ren,'description')->dropDownList($voucher,['onchange' => 'js:return discount();','prompt' => ' -- Select Voucher --'])->label(Yii::t('vouchers','Voucher'))?>
        </div>
        <?php ActiveForm::end(); ?>
      <?php elseif (empty($voucher)) : ?>
        <div class="col-md-3 col-md-offset-2" ><br>
          <!-- input id == from->field created id -->
          <input id="discountitem-description" type="hidden" value=" ">
        </div>
      <?php endif ?>
    </div>
    <div class="col-md-5">
      <a id='refresh' style="display:none;padding-left:30%;" onclick="refresh()"><font style="font-size: 1em;color:blue;float:right;"><?= Yii::t('cart','Reset Coupon') ?></font></a>
    </div>
    <div class="tab-content col-md-5" >
      <table class="table table-total" style="clear: both;table-layout: fixed;">
        <tr>
          <?php 
            $total = $price['total']; 
            $charge = $price['delivery']; 
          ?>
          <td><?= Yii::t('common','Subtotal') ?></td>
          <td class="text-xs-right">RM <font id="subtotal"><?php echo CartController::actionRoundoff1decimal($total) ; ?></font></td>
        </tr>
      <tr class="relative">
        <td><?= Yii::t('common','Delivery Charge') ?><i class="fa fa-question-circle" aria-hidden="true">
          <span class="i-detail i-information"> 
            <?= Yii::t('cart','delivery-charge') ?>
          </span></i>
        </td>
        <td class="text-xs-right">RM <font id="delivery"><?php echo $charge ; ?></font></td>
      </tr>
      <?php 
        $dis = 0;
        if($price['promotion'] == 0 ):?>
      <?php if($time['early'] <= $time['now'] && $time['late'] >= $time['now']):?>
      <tr id="earlytd" class="relative">
        <?php $dis = $earlyDiscount = CartController::actionRoundoff1decimal($total *0.15)?>
        <td><?= Yii::t('common','Early Discount') ?><i class="fa fa-question-circle" aria-hidden="true">
          <span class="i-detail i-information"> 
            <?= Yii::t('cart','early-promo') ?>
          </span></i>
        </td>
  			<td class="text-xs-right" style="color:red;">-RM <font id='early'><?php echo $earlyDiscount?></font></td>
      </tr>
      <?php endif ;?>
      <?php else : ?>
        <tr>
          <td>First Day Discount<i class="fa fa-question-circle" aria-hidden="true">
          <span class="i-detail"> 
              Food Promotion With/Without Food Selection Discount
          </span></i></td>
          <td class="text-xs-left">-<?= $dis = CartController::actionRoundoff1decimal($price['promotion']);?></td>
        </tr>
      <?php endif ;?>
              <!--<tr id="discount" >
                <td><span><b>Discount:</span></td>
                <td class="text-xs-right" id="disamount" value="" style="color: red;"><span></span></td>
              </tr>-->

			 
			
      <!--  <tr id="cs" style="display:none;">
              <td><div id="dis" style=""><input id="codes"></td>
              <td><a class="btn btn-primary" onclick="return discount()">Submit</a></div></td>
        </tr>-->

        <tr style="font-size:28px;">
          <?php $finalPrice = $total - $dis + $charge ;?>
          <td><b><?= Yii::t('common','Total') ?></td>
          <td class="text-xs-right" ><b>RM <font id="total"><?php echo CartController::actionRoundoff1decimal($finalPrice); ?></font></td>
        </tr>
      </table>
	  	
  <?php if($price['promotion'] == 0 ):?>
  <div style="margin-right: 5%;" id="pcs">
	  <div id="pc"><?= Yii::t('cart','Have a') ?> <font style="font-weight:bold;"><?= Yii::t('cart','promo code') ?></font>? 
			<?= Yii::t('cart','Enter it') ?> <a href="javascript:showDiv()" id="showDiv" style="color:#3C3CFF;text-decoration:underline;"><?= Yii::t('cart','here') ?></a>
    </div>
	  <div id="cs" style="display:none;">
        <div id="dis" style=""><input id="codes">
          <a class="raised-btn main-btn" onclick="return discount()"><?= Yii::t('common','Submit') ?></a>
        </div>
		</div>
  </div>
  <?php endif ;?>
    <br>
  </div>  
</div>