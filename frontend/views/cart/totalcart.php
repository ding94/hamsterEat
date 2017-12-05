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
            <?= $form->field($ren,'type')->dropDownList($voucher,['onchange' => 'js:return discount();','prompt' => ' -- Select Voucher --'])->label('Voucher')?>
        </div>
        <?php ActiveForm::end(); ?>
      <?php elseif (empty($voucher)) : ?>
        <div class="col-md-3 col-md-offset-2" ><br>
          <input id="voucherstype-type" type="hidden" value=" ">
        </div>
      <?php endif ?>
    </div>
    <div class="col-md-5">
      <a id='refresh' style="display:none;padding-left:30%;" onclick="refresh()"><font style="font-size: 1em;color:blue;float:right;">Reset Coupon</font></a>
    </div>
    <div class="tab-content col-md-5" >
      <table class="table table-total" style="clear: both;table-layout: fixed;">
        <tr>
          <?php $total = CartController::actionRoundoff1decimal($total) ?>
          <td>Subtotal</td>
          <td class="text-xs-right">RM <font id="subtotal"><?php echo $total ; ?></font></td>
        </tr>
      <tr>
        <td>Delivery Charge<span title="Delivery Charge"><i class="fa fa-question-circle" aria-hidden="true"></i></span></td>
        <td class="text-xs-right">RM <font id="delivery">5.00</font></td>
        </tr>
        <?php if($time['early'] <= $time['now'] && $time['late'] >= $time['now']):?>
          <tr id="earlytd">
            <?php $earlyDiscount = CartController::actionRoundoff1decimal($total *0.2)?>
          <td>Early Discount<span title="Early Discount"><i class="fa fa-question-circle" aria-hidden="true"></i></span></td>
  			 <td class="text-xs-right" style="color:red;">-RM <font id='early'><?php echo $earlyDiscount?></font></td>
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

        <tr style="font-size:20px;">
          <?php $finalPrice = $total - $earlyDiscount + 5 ;?>
          <td><b>TOTAL </td>
          <td class="text-xs-right" ><b>RM <font id="total"><?php echo CartController::actionRoundoff1decimal($finalPrice); ?></font></td>
        </tr>
		
			
      </table>
	  	
		
 <div style="margin-right: 5%;" id="pcs">
	                Have a  <font style="font-weight:bold;">promo code</font>? Enter it <a href="javascript:showDiv()" id="showDiv" style="color:#3C3CFF;text-decoration:underline;">here</a>
	           <div id="cs" style="display:none;">
              <div id="dis" style=""><input id="codes">
                <a class="raised-btn main-btn" onclick="return discount()">Submit</a></div>
			   </div></div><br>
  </div>  
</div>