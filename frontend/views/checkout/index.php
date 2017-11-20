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
<body>
    <div class="tab-content" id="mydetails">
        <h1> Check Out </h1>
        <br>
        <table class="table table-user-info">
            <tr>
                <th colspan = "2"> <h3>Receiver </h3></th>
            </tr>
            <?php $form = ActiveForm::begin(['id' => 'checkout','action' => ['/checkout/order']]); ?>

            <tr>
                <th> Name: </th>
                <td> <?= $form->field($order, 'User_fullname')->textInput()->label('')?> </td>
            </tr>
            <tr>
                <th> Email: </th>
                <td> <?php echo Yii::$app->user->identity->email; ?>  </td>
            </tr>
            <tr>
                <th> Contact No: </th>
                <td> <?= $form->field($order, 'User_contactno')->textInput()->label('')?>  </td>
            </tr>
        </table>
        <table class="table table-user-address">
            <tr>
                <th colspan = "3"> <h3>Delivery Address </h3> <p style='color: grey;'>(Default as Primary)</p></th>
            </tr>

            <tr>
                <td colspan = '2'>
                    <?php 
                        if (!empty($address)) 
                        {
                            foreach ($address as $k => $value) {
                                if ($value['level'] == 1) {
                                    $order->Orders_Location = $value['id'];
                                }
                            }
                            echo $form->field($order, 'Orders_Location')->radioList($addressmap)->label(false);
                        }
                        elseif(empty($address))
                        {
                            echo Html::a("Add New Address",['/user/newaddress'],['class' => 'btn btn-success add-new-address-btn','data-toggle'=>'modal','data-target'=>'#address-modal']);
                        }
                    ?>
                </td>
                <td><?php if(!empty($address)){ echo Html::a('Edit',['/cart/editaddress'],['class' => 'btn btn-primary','data-toggle'=>'modal','data-target'=>'#edit-address-modal','style'=>'float:right']); } ?></td>
            </tr>
           
        </table>
        <br>
        <br>

        <table class="table table-user-paymethod">
            <tr>
                <th><h3> Payment Method </h3></th>
            </tr>

            <tr id='list'>
                <td><?= $form->field($order, 'Orders_PaymentMethod')->radioList(['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'])->label(''); ?></td>
            </tr>
        </table>
            <div class="form-group">
                <!-- when use of onclick function, if return false cant pause posting items, add 'return' in front of function-->
                   <?php echo Html::hiddenInput('area', $area);?>
                <?= Html::submitButton('Place Order', ['class' => 'btn btn-primary', 'onclick'=>'return checkempty()', 'name' => 'placeorder-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </table>
    </div>
</body>