<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use frontend\assets\CheckoutAsset;

$this->title = "Check Out";
CheckoutAsset::register($this);

//new address modal
Modal::begin([
      'header' => '<h2 class="modal-title">New Address</h2>',
      'id'     => 'address-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]);
Modal::end();
//edit address modal
Modal::begin([
      'header' => '<h2 class="modal-title">Edit address</h2>',
      'id'     => 'edit-address-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]);
Modal::end();

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
            <div>
                <h3>Delivery Address </h3> <p style='color: grey;'>(Default as Primary)</p>
                <?php if(!empty($address)):?>
                    <?php foreach($address as $value):?>
                        <?php if($value['level'] == 1):?>
                            <?php $deliveryaddress->location = $value['id'];?>
                            <?php $deliveryaddress->name = $value['recipient']?>
                            <?php $deliveryaddress->contactno = $value['contactno']?>
                        <?php endif ;?>
                    <?php endforeach ;?>
                    <div class="row">
                        <div class="col-md-10"><?= $form->field($deliveryaddress, 'location')->radioList($addressmap)->label(false); ?></div>
                        <div class="col-md-2">
                            <?php echo Html::a('Edit',['/cart/editaddress'],['class' => 'btn btn-primary','data-toggle'=>'modal','data-target'=>'#edit-address-modal']); ?>
                        </div>
                    </div>
                <?php else :?>
                    <?php echo Html::a("Add New Address",['/user/newaddress'],['class' => 'btn btn-success add-new-address-btn','data-toggle'=>'modal','data-target'=>'#address-modal']);
                        ?>
                <?php endif ;?>
            </div>
            <div class="cart-content">
                <h3>Receiver </h3>
                <div class="row">
                    <div class="col-xs-3 cart-label">Name:</div>
                    <div class="col-xs-9">
                        <?= $form->field($deliveryaddress, 'name')->textInput()->label('')?> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3 cart-label">Contact No:</div>
                    <div class="col-xs-9">
                        <?= $form->field($deliveryaddress, 'contactno')->textInput()->label('')?>
                    </div>
                </div>
            </div>
            <div class="cart-content">
                <h3>Payment Method</h3>
                <?= $form->field($order, 'Orders_PaymentMethod')->radioList(['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'])->label(''); ?>
            </div>
            <?php echo Html::hiddenInput('area', $area);?>
            <?php echo Html::hiddenInput('code', $code);?>
            <?= Html::submitButton('Place Order', ['class' => 'btn btn-primary', 'onclick'=>'return checkempty()', 'name' => 'placeorder-button']) ?>
          
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>