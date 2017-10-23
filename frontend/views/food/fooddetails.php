<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;
use frontend\controllers\CartController;
use kartik\widgets\TouchSpin;
use kartik\widgets\DatePicker;
$this->title = "Food Details";

?>
<style>
.value-button {
  border: 1px solid #ddd;
  margin: 0px;
  width: 40px;
  background: #eee;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.value-button:hover {
  cursor: pointer;
}

#input-wrap {
  margin: 0px;
  padding: 0px;
}
#number {
  text-align: center;
  border: none;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  margin: 0px;
  width: 40px;
  height: 40px;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

#fooddetails td{
  padding: 10px 0em 10px 0em;
}

.bordertop{
  border-top: 1px solid #D3D3D3;
}

</style>
<div class="row" style="padding-bottom: 0px">
	<div class="tab-content col-md-12" id="fooddetails">
    <?php if($fooddata->foodPackage == 1) :?>
   
      <?php $form = ActiveForm::begin(['action' => ['UserPackage/package/subscribepackage'],'id' => 'a2cart' ,'method' => 'get']); ?>
    <?php else :?>
      <?php $form = ActiveForm::begin(['id' => 'a2cart']); ?>
    <?php endif ;?>
		<table class="table-user-information" style="width:60%; margin:auto;">

            <tr>         
                  <td colspan = 3> <?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata->PicPath, ['class' => 'img-rounded img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?></td>
            </tr>

            <tr class="bordertop">
                  <td>Food Name:</td>
                  <td colspan = 2> <?php echo $fooddata->Name;?></td>
            </tr>

            <tr class="bordertop">
                  <td>Food Price (RM):</td>
                  <td colspan = 2> <?php echo CartController::actionRoundoff1decimal($fooddata->Price);?></td>
            </tr>

            <tr class="bordertop">
                 <td>Food Description:</td>
                  <td colspan = 2><span style="display: block;overflow-wrap: break-word; word-wrap: break-word; width:148px;"><?php echo $fooddata->Description;?></span></td>
            </tr>
              
            <?php  
              $ftids = "";
              foreach($foodtype as $k=> $foodtype) : 
                $selection = Foodselection::find()->where('Type_ID = :ftid',[':ftid' => $foodtype['ID']])->orderBy(['Price' => SORT_ASC])->all();
                $data = ArrayHelper::map($selection,'ID','typeprice');
                if ($foodtype['Min'] == 1 && $foodtype ['Max'] < 2 ) {
                  ?>
                  <tr class="bordertop">
                    <td>
                      <?php echo $foodtype['TypeName']; ?>
                      <br>
                      <span>*Please Select only 1 item.</span>
                    </td>
                    <td colspan = 2>
                      <?= $form->field($orderItemSelection,'FoodType_ID['.$foodtype['ID'].']')->radioList($data)->label(false); ?>
                    </td>
                  </tr>
              <?php } else if ($foodtype['Min'] == 0){ ?>
                  <tr class="bordertop">
                    <td>
                      <?php echo $foodtype['TypeName']; ?>
                      <br>
                      <span>
                        *Select at most <?php echo $foodtype ['Max']; ?> items.
                      </span>
                    </td>
                    <td colspan = 2>
                      <?= $form->field($orderItemSelection,'FoodType_ID['.$foodtype['ID'].']')->checkboxlist($data)->label(false);?>
                    </td>
                  </tr>
              <?php } else { ?>
                  <tr class="bordertop">
                    <td>
                      <?php echo $foodtype['TypeName']; ?>
                      <br>
                      <span>
                        *Select at least <?php echo $foodtype['Min']; ?> item and at most <?php echo $foodtype ['Max']; ?> items.
                      </span>
                    </td>
                    <td colspan = 2>
                      <?= $form->field($orderItemSelection,'FoodType_ID['.$foodtype['ID'].']')->checkboxlist($data)->label(false);?>
                    </td>
                  </tr>
              <?php } endforeach; ?>
            <tr class="bordertop">
                  <td colspan = 2><?= $form->field($orderitem, 'OrderItem_Remark')->label('Remarks'); ?></td>
            </tr>
            <tr class="bordertop"> 
      				<td colspan="2">
              <?= $form->field($orderitem, 'OrderItem_Quantity')->widget(TouchSpin::classname(), [
                  'options' => [
                      'id'=>'orderitem-orderitem_quantity'.$fooddata->Food_ID,
                  ],
                  'pluginOptions' => [
                      'buttonup_class' => 'btn btn-primary', 
                      'buttondown_class' => 'btn btn-info', 
                      'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
                      'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
                  ],
              ]); ?>
            </td>
            <td colspan="2"><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary pull-right', 'name' => 'addtocart', 'style'=>'margin-bottom:25px;']) ?>
            </td> 
            </tr>
            <?php if($fooddata->foodPackage == 0):?>
			      <tr><td colspan="2"><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary pull-right', 'name' => 'addtocart', 'style'=>'margin-bottom:25px;']) ?>
            </td> </tr> 
            <?php else :?>
            <tr>
              <td>
                <label class="control-label">Select Date to delivery</label>
                <?php
                  echo DatePicker::widget([
                    'name' => 'dateTime',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'format' => 'yyyy/mm/dd/',
                        'multidate' => true,
                        'multidateSeparator' => ',',
                        'startDate' => date('Y/m/d',strtotime("+2 day")),
                    ]
                  ]);
                ?>
              </td>
            </tr>
            
            <?= $form->field($fooddata,'Food_ID')->hiddenInput() ?>
            <tr><td colspan="2"><?= Html::submitButton('Subscribe Food Package', ['class' => 'btn btn-primary pull-right', 'name' => 'addtocart', 'style'=>'margin-bottom:25px;']) ?>
            </td> </tr> 
      <?php endif ;?>
		        
            </table>
            <?php ActiveForm::end(); ?>
      </div>
</div>

