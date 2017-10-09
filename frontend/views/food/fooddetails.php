<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;
use frontend\controllers\CartController;
$this->title = "Food Details";
?>

<div class="container-fluid">
      <h1><center>Food details</h1>
      <br>
	<div class="tab-content col-md-12" id="fooddetails" data-id="<?php echo $fooddata->Food_ID ?>">
		<table class="table table-user-information" style="width:60%; margin:auto;">

            <tr>         
                  <td colspan = 2> <?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata->PicPath, ['class' => 'img-rounded img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?></td>
            </tr>

            <tr>
                  <!--<td>Food Name:</td>-->
                  <td> <?php echo $fooddata->Name;?></td>
            </tr>

            <tr>
                 <!-- <td>Food Price (RM):</td>-->
                  <td> <?php echo CartController::actionRoundoff1decimal($fooddata->Price);?></td>
            </tr>

            <tr>
                 <!-- <td>Food Description:</td>-->
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
              //   echo '<td>'.$foodtype['TypeName'].'<br><span>Select at least '.$foodtype['Min'].' item and at most '.$foodtype ['Max'].' items</span></td>';
                 echo "<td>";     

                 echo $form->field($orderItemSelection,'FoodType_ID['.$k.']')->radioList($data)->label(false);
                 echo "</td>";

                 echo "</tr>";
                 
            }
            else 
            {
                 echo "<tr>";           
                // echo '<td>'.$foodtype['TypeName'].'<br><span>Select at least '.$foodtype['Min'].' item and at most '.$foodtype ['Max'].' items</span></td>';
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
                  <td><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary', 'name' => 'addtocart', 'style'=>'margin-top:25px;','method'=>'post','onclick'=>'form_submit()']) ?></td>
            </tr>

        <?php ActiveForm::end(); ?>
            </table>
      </div>
</div>