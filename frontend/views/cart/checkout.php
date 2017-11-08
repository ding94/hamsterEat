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
            <?php $form = ActiveForm::begin(['id' => 'checkout']); ?>

            <tr>
                <th> Name: </th>
                <td> <?= $form->field($checkout, 'User_fullname')->textInput(['value' => $details['User_FirstName'].' '.$details['User_LastName']])->label('')?> </td>
            </tr>
            <tr>
                <th> Email: </th>
                <td> <?php echo $email; ?>  </td>
            </tr>
            <tr>
                <th> Contact No: </th>
                <td> <?= $form->field($checkout, 'User_contactno')->textInput(['value' => $details['User_ContactNo']])->label('')?>  </td>
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
                                    $checkout->Orders_Location = $value['id'];
                                }
                            }
                            echo $form->field($checkout, 'Orders_Location')->radioList($addressmap)->label(false);
                        }
                        elseif(empty($address))
                        {
                            echo Html::a("Add New Address",['/cart/newaddress'],['class' => 'btn btn-success add-new-address-btn','data-toggle'=>'modal','data-target'=>'#address-modal']);
                        }
                    ?>
                </td>
                <td><?php if(!empty($address)){ echo Html::a('Edit',['/cart/editaddress'],['class' => 'btn btn-primary','data-toggle'=>'modal','data-target'=>'#edit-address-modal','style'=>'float:right']); } ?></td>
            </tr>
            <tr>
                <th> Area: </th>
                <td> <?= $session['area']; ?></td>
                <td></td>
            </tr>

            <tr>
                <th> Postcode: </th>
                <td> <?= $session['postcode']; ?></td>
                <td></td>
            </tr>
        </table>
        <br>
        <br>

        <table class="table table-user-paymethod">
            <tr>
                <th><h3> Payment Method </h3></th>
            </tr>

            <tr id='list'>
                <td><?= $form->field($checkout, 'Orders_PaymentMethod')->radioList(['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'])->label(''); ?></td>
            </tr>
        </table>
            <div class="form-group">
                <!-- when use of onclick function, if return false cant pause posting items, add 'return' in front of function-->
                <?= Html::submitButton('Place Order', ['class' => 'btn btn-primary', 'onclick'=>'return checkempty()', 'name' => 'placeorder-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </table>
    </div>
</body>