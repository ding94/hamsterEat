<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\web\Session;
use frontend\assets\CheckoutAsset;

$this->title = Yii::t('cart','Check Out');
CheckoutAsset::register($this);

//new address modal
Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('cart','Edit address').'</h2>',
      'id'     => 'address-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('common','Close');.'</a>',
]);
Modal::end();
//edit address modal
Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('cart','Edit address').'</h2>',
      'id'     => 'edit-address-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('common','Close');.'</a>',
]);
Modal::end();

?>
    <div class="tab-content" id="mydetails">
        <h1><?= Yii::t('cart','Check Out');?></h1>
        <br>
        <table class="table table-user-info">
            <tr>
                <th colspan = "2"> <h3><?= Yii::t('cart','Receiver');?></h3></th>
            </tr>
            <?php $form = ActiveForm::begin(['id' => 'checkout']); ?>

            <tr>
                <th><?= Yii::t('common','Name');?>:</th>
                <td> <?= $form->field($checkout, 'User_fullname')->textInput(['value' => $details['User_FirstName'].' '.$details['User_LastName']])->label('')?> </td>
            </tr>
            <tr>
                <th><?= Yii::t('common','Email');?>:</th>
                <td> <?php echo $email; ?>  </td>
            </tr>
            <tr>
                <th><?= Yii::t('common','Contact No');?>:</th>
                <td> <?= $form->field($checkout, 'User_contactno')->textInput(['value' => $details['User_ContactNo']])->label('')?>  </td>
            </tr>
        </table>
        <table class="table table-user-address">
            <tr>
                <th colspan = "3"> <h3><?= Yii::t('cart','Delivery Address');?></h3> <p style='color: grey;'>(Default as Primary)</p></th>
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
                            echo Html::a(Yii::t('cart','Add New Address'),['/user/newaddress'],['class' => 'raised-btn main-btn add-new-address-btn','data-toggle'=>'modal','data-target'=>'#address-modal']);
                        }
                    ?>
                </td>
                <td><?php if(!empty($address)){ echo Html::a(Yii::t('common','Edit'),['/cart/editaddress'],['class' => 'raised-btn secondary-btn','data-toggle'=>'modal','data-target'=>'#edit-address-modal','style'=>'float:right']); } ?></td>
            </tr>
            <tr>
                <th><?= Yii::t('cart','Area');?>:</th>
                <td> <?= $session['area']; ?></td>
                <td></td>
            </tr>

            <tr>
                <th><?= Yii::t('cart','Postcode');?>:</th>
                <td> <?= $session['postcode']; ?></td>
                <td></td>
            </tr>
        </table>
        <br>
        <br>

        <table class="table table-user-paymethod">
            <tr>
                <th><h3><?= Yii::t('common','Payment Method');?></h3></th>
            </tr>

            <tr id='list'>
                <td><?= $form->field($checkout, 'Orders_PaymentMethod')->radioList(['Account Balance'=>'Account Balance','Cash on Delivery'=>'Cash on Delivery'])->label(''); ?></td>
            </tr>
            <tr>
                <td><?= Html::submitButton(Yii::t('common','Place Order'), ['class' => 'raised-btn main-btn btn-lg', 'onclick'=>'return checkempty()', 'name' => 'placeorder-button']) ?></td>
            </tr>
        </table>
            <?php ActiveForm::end(); ?>
        </table>
    </div>