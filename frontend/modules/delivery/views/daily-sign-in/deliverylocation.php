<?php

/* @var $this yii\web\View */
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use common\models\Area;
use frontend\assets\DeliveryLocationAsset;

$this->title = 'Set Up Your Delivery Location';
DeliveryLocationAsset::register($this);
?>
<div class="container" id="delivery-location-container">
    <div class="delivery-location-header">
        <div class="delivery-location-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
          <div class="dropdown-url">
                 <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => 'Go To ...',
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
            <div class="nav-url">
              <ul id="delivery-location-nav" class="nav nav-pills nav-stacked">
                  <li role="presentation"><?php echo Html::a("Deliveryman Orders",['/order/deliveryman-orders'],['class'=>'btn-block'])?></li>
                  <li role="presentation"><?php echo Html::a("Deliveryman Orders History",['/order/deliveryman-order-history'],['class'=>'btn-block'])?></li>
                  <li role="presentation" class="active"><?php echo Html::a("Delivery Location",['/Delivery/daily-sign-in/delivery-location'],['class'=>'btn-block'])?></li>
              </ul>
            </div>
        </div>
        <div id="delivery-location-content" class="col-sm-10">
            <table class="table table-user-info delivery-location-table">
              <thead>
                <tr>
                  <th>Area Group</th>
                  <th>Area</th>
                  <th>Postcode</th>
                  <th>State</th>
                </tr>
              </thead>
              <?php
                foreach ($area as $area) :
                  $amount = Area::find()->where('Area_Group = :ag', [':ag'=>$area['Area_Group']])->all();
                  $count = count($amount);
                  foreach ($amount as $amount) :
                    if ($count > 0)
                    {
              ?>
                <tr>
                  <td class="border-left" rowspan="<?php echo $count; ?>"><?php echo $amount['Area_Group']; ?></td>
                  <td><?php echo $amount['Area_Area']; ?></td>
                  <td><?php echo $amount['Area_Postcode']; ?></td>
                  <td><?php echo $amount['Area_State']; ?></td>
                </tr>
              <?php
                } else {
              ?>
                <tr>
                  <td><?php echo $amount['Area_Area']; ?></td>
                  <td><?php echo $amount['Area_Postcode']; ?></td>
                  <td><?php echo $amount['Area_State']; ?></td>
                </tr>
              <?php
                }
                $count = 0;
              endforeach;
            endforeach;
              ?>
            </table>
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
            <?= $form->field($find, 'DeliveryMan_AreaGroup')->widget(Select2::classname(), [
              'data' => $postcodeArray,
              'options' => ['placeholder' => 'Select area group ...','id'=>'postcode-select']])->label('Select Your Delivery Location'); 
            ?>
            <?= Html::submitButton('Confirm', ['class' => 'raised-btn main-btn', 'name' => 'insert-button']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>