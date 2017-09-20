<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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

            <?php $form = ActiveForm::begin(['id' => 'form-newrestaurant']); ?>
            
            <tr>
                  <td><?= $form->field($orderitem, 'OrderItem_Quantity')->textInput(['type' => 'number', 'value' => "1"])?></td>
                  <td><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary', 'name' => 'addtocart-button', 'style'=>'margin-top:25px;']) ?></td>
            </tr>


        <?php ActiveForm::end(); ?>
            </table>
      </div>
</div>