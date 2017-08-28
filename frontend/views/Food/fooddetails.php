<?php
use yii\helpers\Html;
?>

<div class="container">
	<div class="tab-content col-md-7 col-md-offset-1" id="userprofile">
		<table class="table table-user-information"><h1>Food details</h1>

            <tr>         
            <td> <img class="img-rounded img-responsive" style="height:250px" src="<?php echo $food->Food_FoodPicPath; ?>"></td>
            </tr>

            <tr>
            <td>Food Name:</td>
            <td> <?php echo $food->Food_Name;?></td>
            </tr>

            <tr>
            <td>Food Type:</td>
            <td> <?php echo $food->Food_Type;?></td>
            </tr>

            <tr>
            <td>Food Price:</td>
            <td> <?php echo $food->Food_Price;?></td>
            </tr>

            <tr>
            <td>Food Description:</td>
            <td> <?php echo $food->Food_Desc;?></td>
            </tr>

            <tr>				
            <td> <?= Html::a('Add to Cart', [''], ['class'=>'btn btn-primary']) ?> </td>
            </tr>
            </table>
            </div>
            </div>