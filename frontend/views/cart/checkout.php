<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use frontend\assets\CheckoutAsset;

$this->title = "Check Out";
CheckoutAsset::register($this);
?>
<body  >
    <div class="tab-content" id="mydetails">
        <h1> Check Out </h1>
        <br>
        <table class="table table-user-info">
            <tr>
                <th colspan = "2"> <h3>My Details</h3> </th>
            </tr>

            <tr>
                <th> Full Name: </th>
                <td> <?php echo $fullname ?> </td>
            </tr>

            <tr>
                <th> Email: </th>
                <td> <?php echo $myemail ?> </td>
            </tr>

            <tr>
                <th> Contact No: </th>
                <td> <?php echo $mycontactno ?> </td>
            </tr>
        </table>
        <br>
        <br>

        <?php $form = ActiveForm::begin(['id' => 'checkout']); ?>

        <table class="table table-user-address">
            <tr>
                <th colspan = "2"> <h3>My Delivery Address </h3></th>
            </tr>

            <tr>
                <th> Unit No: </th>
                <td> <?= $form->field($checkout, 'Orders_Location')->textInput(['value' => ""])->label('')?></td>
            </tr>

            <tr>
                <th> Street: </th>
                <td> <?= $form->field($checkout, 'Orders_Area')->textInput(['value' => ""])->label('')?></td>
            </tr>

            <tr>
                <th> Area: </th>
                <td> <?= $session['area']; ?>
            </tr>

            <tr>
                <th> Postcode: </th>
                <td> <?= $session['postcode']; ?>
            </tr>
        </table>
        <br>
        <br>

        <table class="table table-user-paymethod">
            <tr>
                <th><h3> Payment Method </h3></th>
            </tr>

            <tr id='list'>
                <td><?= $form->field($checkout, 'Orders_PaymentMethod')->radioList(['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'])->label(''); ?>
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