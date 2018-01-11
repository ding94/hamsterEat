<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use frontend\assets\CheckoutAsset;
use kartik\widgets\Select2; 
use yii\web\JsExpression;
use \yii\helpers\Url;
use common\models\Company\Company;

$this->title = "Check Out";
CheckoutAsset::register($this);

?>
        <div class="container">
       <div class="checkout-progress-bar">
         <div class="circle done">
           <span class="label"><i class="fa fa-check"></i></span>
           <span class="title">Cart</span>
         </div>
         <span class="bar done"></span>
         <div class="circle active">
           <span class="label"><i class="fa fa-cart-arrow-down"></i></span>
           <span class="title">Checkout</span>
         </div>
         <span class="bar"></span>
         <div class="circle deactive">
           <span class="label"><i class="fa fa-credit-card"></i></span>
           <span class="title">Payment</span>
         </div>
       </div> 
      </div>
<div class="container">
    <div class="tab-content" id="mydetails">
        <div class="cart-header">
            <div class="header-title">Checkout</div>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'checkout','action' => ['/checkout/order']]); ?>
        <div class="cart-detail">
         
            <div class="company">
              <h3>Company Address </h3>
              <?= $form->field($deliveryaddress,'cid')->dropDownList($companymap,[ 'prompt' => ' -- Select Company --'])->label(false);?>
            </div>
        
            <div class="cart-content">
                <h3>Receiver </h3>
                <div class="row">
                    <div class="col-xs-3 cart-label">Name:</div>
                    <div class="col-xs-9">
                        <?= $form->field($deliveryaddress, 'name')->textInput(['value'=>$username])->label('')?> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3 cart-label">Contact No:</div>
                    <div class="col-xs-9">
                        <?= $form->field($deliveryaddress, 'contactno')->textInput(['value'=>$contact])->label('')?>
                    </div>
                </div>
            </div>
            <div class="cart-content">
                <h3>Payment Method</h3>
                <?= $form->field($order, 'Orders_PaymentMethod')->radioList(['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'])->label(''); ?>
            </div>
            <?php 
              foreach($postdata['cid'] as $id) :
                echo Html::hiddenInput('cid[]', $id);
              endforeach;
            ?>
            <?php echo Html::hiddenInput('area', $postdata['area']);?>
            <?php echo Html::hiddenInput('code', $postdata['code']);?>
            <?= Html::submitButton('Place Order', ['class' => 'raised-btn main-btn', 'onclick'=>'return checkempty()', 'name' => 'placeorder-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>