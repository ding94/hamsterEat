<?php
use yii\helpers\Html;
use common\models\Food;
use common\models\Orderitemselection;
use common\models\Foodtype;
Use common\models\Foodselection;
Use common\models\Orders;
use yii\bootstrap\ActiveForm;

$this->title = "My Cart";
?>

<div class="container">
  <div class="tab-content col-md-7 col-md-offset-1" id="userprofile">
    <table class="table table-user-information" style="width:1100px; margin-left:-15%;">
    <h1 style = "margin-left: -10%; margin-top:-4%;">Cart</h1><br>
      <tr>
        <th><center>Picture</th>
        <th><center>Food Name</th>
        <th><center>Unit Price (RM)</th>
        <th><center>Quantity</th>
        <th><center>Selections</th>
        <th><center>Selections Price (RM)</th>
        <th><center>LineTotal (RM)</th>
        <th><center>Remarks</th>
      </tr>

      <?php
      foreach ($cartitems as $cartitems) :
        $fooddetails = Food::find()->where('Food_ID = :fid',[':fid'=>$cartitems['Food_ID']])->one();

        echo "<tr>";
        ?>
        <td><center><img class="img-rounded img-responsive" style="height:60px; width:70px;;" src="<?php echo "/hamsterEat/frontend/web/imageLocation/".$fooddetails['Food_FoodPicPath']; ?>"></td>
        <?php
        echo "<td><center>".$fooddetails['Food_Name']."</td>";
        echo "<td align="."right>".$fooddetails['Food_Price']."</td>";
        echo "<td><center>".$cartitems['OrderItem_Quantity']."</td>";
        $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$cartitems['Order_ID']])->all();
        echo "<td><center>";
        foreach ($selections as $selections) :
          $selectionname = Foodselection::find()->where('Selection_ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
          $selectiontype = Foodtype::find()->where('FoodType_ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
          echo $selectiontype['Selection_Type'].': &nbsp;'.$selectionname['Selection_Name'];
          echo "<br>";
        endforeach;
        echo "</td>";
        echo "<td align="."right>".$cartitems['OrderItem_SelectionTotal']."</td>";
        echo "<td align="."right>".$cartitems['OrderItem_LineTotal']."</td>";
        echo "<td><center> </td>";
        echo "</tr>";
      endforeach;
      $did = Orders::find()->where('Delivery_ID = :did',[':did'=>$deliveryid])->one();
      //var_dump($did);exit;
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td><center><strong> Subtotal (RM): </strong></td>";
        echo "<td align="."right>".$did['Orders_Subtotal']."</td>";
      echo "</tr>";
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td><center><strong> Delivery Charge (RM): </strong></td>";
        echo "<td align="."right>".$did['Orders_DeliveryCharge']."</td>";
      echo "</tr>";
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td><center><strong> Total (RM): </strong></td>";
        echo "<td align="."right><strong>".$did['Orders_TotalPrice']."</strong></td>";
      echo "</tr>";
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";?>
        <td id ="extend"> </td>
        <td  id ="label" style="display: none"><strong> Discount Code: </strong></td>
        <?php $form = ActiveForm::begin(); ?>
        <td><div > <input id ="input" style="display: none"></input> </div></td>
        <?php ActiveForm::end(); ?>
        <td style="display: none" id="apply"><div ><a font="color: blue">Apply</a></div></td>
        <td id ="hide2"><a onclick="showHidden()">Have a coupon ? Click Me</a></td>
        
          <?php
      echo "</tr>";
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td align="."right><strong>".Html::a('Checkout', ['checkout', 'did'=>$deliveryid], ['class'=>'btn btn-primary'])."</strong></td>";
      echo "</tr>";
      ?>
    </table>
  </div>
</div>

<script >
  function showHidden()
  {
      document.getElementById("label").style.display ='block';
      document.getElementById("input").style.display ='block';
      document.getElementById("apply").style.display ='block';
      document.getElementById("hide2").style.display ='none';
      document.getElementById("extend").style.display ='none';
  }

</script>