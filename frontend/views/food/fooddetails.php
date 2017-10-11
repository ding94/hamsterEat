<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;
use frontend\controllers\CartController;
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
    <?php $form = ActiveForm::begin(['id' => 'a2cart']); ?>
		<table class="table-user-information" style="width:60%; margin:auto;">

            <tr>         
                  <td colspan = 2> <?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata->PicPath, ['class' => 'img-rounded img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?></td>
            </tr>

            <tr class="bordertop">
                  <td>Food Name:</td>
                  <td> <?php echo $fooddata->Name;?></td>
            </tr>

            <tr class="bordertop">
                  <td>Food Price (RM):</td>
                  <td> <?php echo CartController::actionRoundoff1decimal($fooddata->Price);?></td>
            </tr>

            <tr class="bordertop">
                 <td>Food Description:</td>
                  <td> <?php echo $fooddata->Description;?></td>
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
                      <span>Please Select only 1 item.</span>
                    </td>
                    <td>
                      <?= $form->field($orderItemSelection,'FoodType_ID['.$k.']')->radioList($data)->label(false); ?>
                    </td>
                  </tr>
              <?php } else if ($foodtype['Min'] == 0){ ?>
                  <tr class="bordertop">
                    <td>
                      <?php echo $foodtype['TypeName']; ?>
                      <br>
                      <span>
                        Select at most <?php echo $foodtype ['Max']; ?> items.
                      </span>
                    </td>
                    <td>
                      <?= $form->field($orderItemSelection,'FoodType_ID['.$k.']')->checkboxlist($data)->label(false);?>
                    </td>
                  </tr>
              <?php } else { ?>
                  <tr class="bordertop">
                    <td>
                      <?php echo $foodtype['TypeName']; ?>
                      <br>
                      <span>
                        Select at least<?php echo $foodtype['Min']; ?> item and at most <?php echo $foodtype ['Max']; ?> items.
                      </span>
                    </td>
                    <td>
                      <?= $form->field($orderItemSelection,'FoodType_ID['.$k.']')->checkboxlist($data)->label(false);?>
                    </td>
                  </tr>
              <?php } endforeach; ?>
            <tr class="bordertop">
                  <td colspan = 2><?= $form->field($orderitem, 'OrderItem_Remark')->label('Remarks'); ?></td>
                  <td> </td>
            </tr>
            <tr class="bordertop"> 
              <td><b>Order Item Quantity</b></td>
      				<td>
      				<?= $form->field($orderitem, 'OrderItem_Quantity',['template' => '<div class="input-group"><span class="value-button input-group-addon" id="decrease" onclick="decreaseValue()" value="Decrease Value">-</span>{input}{error}{hint}<span class="value-button input-group-addon" id="increase" onclick="increaseValue()" value="Increase Value">+</span></div>'])->textInput(['type' => 'number', 'value' => "1",'style'=>'width:80px'])->label(false)?>
            </td>
            </tr>
			
			<tr><td><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary', 'name' => 'addtocart', 'style'=>'margin-bottom:25px;']) ?>
      </td> </tr> 
            </table>
            <?php ActiveForm::end(); ?>
      </div>
</div>

<script>

</script>