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

</style>
<div class="container">
      <h1><center>Food details</h1>
      <br>
	<div class="tab-content col-md-12" id="fooddetails">
		<table class="table table-user-information" style="width:60%; margin:auto;">

            <tr>         
                  <td colspan = 2> <?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata->PicPath, ['class' => 'img-rounded img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?></td>
            </tr>

            <tr>
                  <td>Food Name:</td>
                  <td> <?php echo $fooddata->Name;?></td>
            </tr>

            <tr>
                  <td>Food Price (RM):</td>
                  <td> <?php echo CartController::actionRoundoff1decimal($fooddata->Price);?></td>
            </tr>

            <tr>
                 <td>Food Description:</td>
                  <td> <?php echo $fooddata->Description;?></td>
            </tr>
              
     
            <?php $form = ActiveForm::begin(['id' => 'a2cart']); ?>
           
            <?php
            $ftids = "";
            foreach($foodtype as $k=> $foodtype) : 
            $selection = Foodselection::find()->where('Type_ID = :ftid',[':ftid'=>$foodtype['ID']])->all();
            $data = ArrayHelper::map($selection,'ID','typeprice');

            if($foodtype['Min'] == 0 && $foodtype ['Max'] < 2 || $foodtype['Min'] == 1 && $foodtype ['Max'] < 2 )
            {
                 echo "<tr>";           
                 echo '<td>'.$foodtype['TypeName'].'<br><span>Select at least '.$foodtype['Min'].' item and at most '.$foodtype ['Max'].' items</span></td>';
                 echo "<td>";     

                 echo $form->field($orderItemSelection,'FoodType_ID['.$k.']')->radioList($data)->label(false);
                 echo "</td>";

                 echo "</tr>";
                 
            }
            else 
            {
                 echo "<tr>";           
                 echo '<td>'.$foodtype['TypeName'].'<br><span>Select at least '.$foodtype['Min'].' item and at most '.$foodtype ['Max'].' items</span></td>';
                 echo "<td>";     

                 echo $form->field($orderItemSelection,'FoodType_ID['.$k.']')->checkboxlist($data)->label(false);
                 echo "</td>";

                 echo "</tr>";
                 
            }
             endforeach; 
                 ?> 
            <tr>
                  <td colspan = 2><?= $form->field($orderitem, 'OrderItem_Remark')->label('Remarks'); ?></td>
                  <td> </td>
            </tr>
            <tr> <td><b>Order Item Quantity</b></td>
      				<td>
      				<?= $form->field($orderitem, 'OrderItem_Quantity',['template' => '<div class="input-group"><span class="value-button input-group-addon" id="decrease" onclick="decreaseValue()" value="Decrease Value">-</span>{input}{error}{hint}<span class="value-button input-group-addon" id="increase" onclick="increaseValue()" value="Increase Value">+</span></div>'])->textInput(['type' => 'number', 'value' => "1",'style'=>'width:80px'])->label(false)?>
            </td>
            </tr>
			
			<tr><td><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary', 'name' => 'addtocart', 'style'=>'margin-bottom:25px;']) ?>
      </td> </tr> <?php ActiveForm::end(); ?>
            </table>
      </div>
</div>

<script>
function increaseValue() {
  var value = parseInt(document.getElementById('orderitem-orderitem_quantity').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('orderitem-orderitem_quantity').value = value;
}

function decreaseValue() {
  var value = parseInt(document.getElementById('orderitem-orderitem_quantity').value, 10);
  value = isNaN(value) ? 0 : value;
  value < 1 ? value = 1 : '';
  value--;
  document.getElementById('orderitem-orderitem_quantity').value = value;
}
</script>