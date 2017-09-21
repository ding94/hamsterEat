<?php
use yii\helpers\Html;
use common\models\food;
?>

<div class="container">
  <div class="tab-content col-md-7 col-md-offset-1" id="userprofile">
    <table class="table table-user-information"><h1>Cart</h1>
      <tr>
        <th><center>Picture</th>
        <th><center>Food Name</th>
        <th><center>Unit Price (RM)</th>
        <th><center>Quantity</th>
        <th><center>LineTotal (RM)</th>
      </tr>

      <?php
      foreach ($cartitems as $cartitems) :
        $fooddetails = food::find()->where('Food_ID = :fid',[':fid'=>$cartitems['Food_ID']])->one();

        echo "<tr>";
        ?>
        <td><center><img class="img-rounded img-responsive" style="height:40px; width:50px;;" src="<?php echo "/hamsterEat/frontend/web/imageLocation/".$fooddetails['Food_FoodPicPath']; ?>"></td>
        <?php
        echo "<td><center>".$fooddetails['Food_Name']."</td>";
        echo "<td align="."right>".$fooddetails['Food_Price']."</td>";
        echo "<td><center>".$cartitems['OrderItem_Quantity']."</td>";
        echo "<td align="."right>".$cartitems['OrderItem_LineTotal']."</td>";
        echo "</tr>";
      endforeach;  
      ?>
    </table>
  </div>
</div>