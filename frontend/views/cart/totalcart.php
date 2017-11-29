<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\controllers\CartController;
use frontend\assets\CartAsset;

CartAsset::register($this);
?>
<div class="container" >
    <div class="col-md-3 col-md-offset-2" id='voucher'>
      <?php if (!empty($voucher)): ?>
        <?php $form = ActiveForm::begin(); ?>
        <div>
            <?= $form->field($ren,'type')->dropDownList($voucher,['onchange' => 'js:return discount();','prompt' => ' -- Select Coupons --'])->label('Coupons')?>
            <a id='show' style="padding-left:30%;" onclick="show()"><font style="font-size: 1em;color:blue;">Other promote code</font></a>
            <div id="dis" style="display: none;"><input id="codes"><a class="btn btn-primary" onclick="return discount()">Submit</a></div>
        </div>
        <?php ActiveForm::end(); ?>
      <?php elseif (empty($voucher)) : ?>
          <input id="voucherstype-type" type="hidden" value=" ">
          <a id='show' style="padding-left:30%;" onclick="show2()"><font style="font-size: 1em;color:blue;">Other promote code</font></a>
          <div id="dis" style="display: none;"><input id="codes"><a class="btn btn-primary" style="margin: 5px;" onclick="return discount()">Submit</a></div>
      <?php endif ?>
    </div>
    <div class="col-md-5">
      <a id='refresh' style="display:none;padding-left:30%;" onclick="refresh()"><font style="font-size: 1em;color:blue;float:right;">Reset Coupon</font></a>
    </div>
    <div class="tab-content col-md-5" >
      <table class="table table-total" style="clear: both;table-layout: fixed;">
        <tr>
          <?php $total = CartController::actionRoundoff1decimal($total) ?>
          <td><b>Subtotal (RM):</td>
          <td class="text-xs-right" id="subtotal"><?php echo $total ; ?></td>
        </tr>
      <tr>
        <td><b>Delivery Charge (RM):</td>
        <td class="text-xs-right" id="delivery">5.00</td>
        </tr>
        <?php if($time['early'] <= $time['now'] && $time['late'] >= $time['now']):?>
          <tr>
            <?php $earlyDiscount = CartController::actionRoundoff1decimal($total *0.2)?>
          <td><b>Early Discount (RM):</td>
  			   <td class="text-xs-right" id='early' style="color:red;">-<?php echo $earlyDiscount?></td>
          </tr>
        <?php endif ;?>
              <!--<tr id="discount" >
                <td><span><b>Discount:</span></td>
                <td class="text-xs-right" id="disamount" value="" style="color: red;"><span></span></td>
              </tr>-->
        <tr>
          <?php $finalPrice = $total - $earlyDiscount + 5 ;?>
          <td><b>Total (RM): </td>
          <td class="text-xs-right" id="total"><?php echo CartController::actionRoundoff1decimal($finalPrice); ?></td>
        </tr>
      </table>
      <?php $form = ActiveForm::begin(['action' =>['checkout/index'],'method' => 'get']); ?>
        <?php echo Html::hiddenInput('area', $area);?>
        <?php echo Html::hiddenInput('code', '');?>
        <?php echo Html::submitButton('Checkout', ['class' => 'btn btn-b']);?>
      <?php ActiveForm::end(); ?>
  </div>  
</div>