<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;
$this->title = "Food Details";
?>

<div class="container">
      <h1><center>Food details</h1>
      <br>
	<div class="tab-content col-md-12" id="fooddetails">
		<table class="table table-user-information" style="width:60%; margin:auto;">

            <tr>         
                  <td> <img class="img-rounded img-responsive" style="height:250px; width:350px; margin-left: 25%;" src="<?php echo "/hamsterEat/frontend/web/imageLocation/".$fooddata->Food_FoodPicPath; ?>"></td>
            </tr>

            <tr>
                  <td>Food Name:</td>
                  <td> <?php echo $fooddata->Food_Name;?></td>
            </tr>

            <tr>
                  <td>Food Type:</td>
                  <td> <?php echo $fooddata->Food_Type;?></td>
            </tr>

            <tr>
                  <td>Food Price (RM):</td>
                  <td> <?php echo $fooddata->Food_Price;?></td>
            </tr>

            <tr>
                  <td>Food Description:</td>
                  <td> <?php echo $fooddata->Food_Desc;?></td>
            </tr>
              
     
            <?php $form = ActiveForm::begin(['id' => 'a2cart']); ?>
           
            <?php
            $ftids = "";
            foreach($foodtype as $k=> $foodtype) : 
            $selection = Foodselection::find()->where('FoodType_ID = :ftid',[':ftid'=>$foodtype['FoodType_ID']])->all();
            $data = ArrayHelper::map($selection,'Selection_ID','typeprice');

            if($foodtype['FoodType_Min'] == 0 && $foodtype ['FoodType_Max'] < 2 || $foodtype['FoodType_Min'] == 1 && $foodtype ['FoodType_Max'] < 2 )
            {
                 echo "<tr>";           
                 echo '<td>'.$foodtype['Selection_Type'].'<br><span>Select at least '.$foodtype['FoodType_Min'].' item and at most '.$foodtype ['FoodType_Max'].' items</span></td>';
                 echo "<td>";     

                 echo $form->field($orderItemSelection,'FoodType_ID['.$k.']')->radioList($data)->label(false);
                 echo "</td>";

                 echo "</tr>";
                 
            }
            else 
            {
                 echo "<tr>";           
                 echo '<td>'.$foodtype['Selection_Type'].'<br><span>Select at least '.$foodtype['FoodType_Min'].' item and at most '.$foodtype ['FoodType_Max'].' items</span></td>';
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
            <tr>
                  <td><?= $form->field($orderitem, 'OrderItem_Quantity')->textInput(['type' => 'number', 'value' => "1"])?></td>
                  <td><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary', 'name' => 'addtocart', 'style'=>'margin-top:25px;']) ?></td>
            </tr>

        <?php ActiveForm::end(); ?>
            </table>
      </div>
</div>