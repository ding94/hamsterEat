<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;


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
              
     
            <?php $form = ActiveForm::begin(['id' => 'fooddetails']); ?>
           
            <?php
            foreach($foodtype as $foodtype) : 
            $selection = Foodselection::find()->where('FoodType_ID = :ftid',[':ftid'=>$foodtype['FoodType_ID']])->all();
            $data = ArrayHelper::map($selection,'Selection_ID','typeprice');
            if($foodtype['FoodType_Min'] == 0 && $foodtype ['FoodType_Max'] < 2)
            { 
                 $foodtypeid = $foodtype['FoodType_ID'];
                 echo "<tr>";           
                 echo '<td>'.$foodtype['Selection_Type'].'</td>';
                 echo "<td>";     
                 echo $form->field($orderItemSelection,'Selection_ID')->radioList($data)->label(false);
                 echo "</td>";
                 echo "</tr>";
                 Html::hiddenInput("foodtypeid", $foodtypeid);
            }
            elseif ($foodtype['FoodType_Min'] == 1 && $foodtype ['FoodType_Max'] < 2)
            {
                  $foodtypeid = $foodtype['FoodType_ID'];
                  echo "<tr>";           
                  echo '<td>'.$foodtype['Selection_Type'].'</td>';
                  echo "<td>";     
                  echo $form->field($orderItemSelection,'Selection_ID')->radioList($data)->label(false);
                  echo "</td>";
                  echo "</tr>";
                  Html::hiddenInput("foodtypeid", $foodtypeid);
            }
            else 
            {
                 $foodtypeid = $foodtype['FoodType_ID'];
                 echo "<tr>";           
                 echo '<td>'.$foodtype['Selection_Type'].'</td>';
                 echo "<td>";     
                 echo $form->field($orderItemSelection,'Selection_ID')->checkboxlist($data)->label(false);
                 echo "</td>";
                 echo "</tr>";
                 Html::hiddenInput("foodtypeid", $foodtypeid);
            }
             endforeach; 
                 ?> 
            
            <tr>
                  <td><?= $form->field($orderitem, 'OrderItem_Quantity')->textInput(['type' => 'number', 'value' => "1"])?></td>
                  <td><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary', 'name' => 'addtocart-button', 'style'=>'margin-top:25px;']) ?></td>
            </tr>

        <?php ActiveForm::end(); ?>
            </table>
      </div>
</div>