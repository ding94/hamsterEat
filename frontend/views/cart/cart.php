<?php
use yii\helpers\Html;
use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselectiontype;
Use common\models\food\Foodselection;
Use common\models\Orders;
use yii\bootstrap\ActiveForm;
use frontend\controllers\CartController;

$this->title = "My Cart";
?>
<?php
			if($cartitems == true)
			{
		?>
<div class="container">
  <div class="tab-content col-md-7 col-md-offset-1" id="cart">

    <table class="table table-user-information" style="width:1100px; margin-left:-15%;">
    <h1 style = "margin-left: -10%; margin-top:-4%;">Cart</h1><br>
      <tr>
        <th colspan = 2><center>Food Name</th>
        <th><center>Unit Price (RM)</th>
        <th><center>Quantity</th>
        <th><center>Selections</th>
        <th><center>Selections Price (RM)</th>
        <th><center>LineTotal (RM)</th>
        <th><center>Remarks</th>
        <th><center>Remove</th>
      </tr>

      <?php
      foreach ($cartitems as $cartitems) :
        $fooddetails = Food::find()->where('Food_ID = :fid',[':fid'=>$cartitems['Food_ID']])->one();

        echo "<tr>";
        ?>
        <td><center><?php echo Html::img('@web/imageLocation/foodImg/'.$fooddetails['PicPath'], ['class' => 'img-responsive','style'=>'height:60px; width:90px; margin:auto;']); ?></td>
        <?php
        echo "<td><center>".$fooddetails['Name']."</td>";
        echo "<td align="."right>".CartController::actionRoundoff1decimal($fooddetails['Price'])."</td>"; 
        echo "<td><center>".$cartitems['OrderItem_Quantity']."</td>";
        $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$cartitems['Order_ID']])->all();
        echo "<td><center>";
        foreach ($selections as $selections) :
          $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
          $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
          if (!is_null($selectionname['ID']))
          {
            echo $selectiontype['TypeName'].': &nbsp;'.$selectionname['Name'];
            echo "<br>";
          }
        endforeach;
        echo "</td>";
        echo "<td align="."right>".CartController::actionRoundoff1decimal($cartitems['OrderItem_SelectionTotal'])."</td>";
        echo "<td align="."right>".CartController::actionRoundoff1decimal($cartitems['OrderItem_LineTotal'])."</td>";
        echo "<td><center>".$cartitems['OrderItem_Remark']."</td>";
        echo "<td class='pull-right'>".Html::a('', ['delete','oid'=>$cartitems['Order_ID']], ['class'=>'btn btn-danger fa fa-trash'])."</td>";
        echo "</tr>";
      endforeach;
      $did = Orders::find()->where('Delivery_ID = :did',[':did'=>$did])->one();
      //var_dump($did);exit;
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td><center><strong> Subtotal (RM): </strong></td>"; ?>
        <td align=right> <font id="subtotal"><?php echo CartController::actionRoundoff1decimal($did['Orders_Subtotal']); ?></font></td><?php
      echo "</tr>";
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td><center><strong> Delivery Charge (RM): </strong></td>";?>
        <td align=right> <font id="delivery"><?php echo CartController::actionRoundoff1decimal($did['Orders_DeliveryCharge']); ?></font></td><?php
      echo "</tr>";

      $timenow = Yii::$app->formatter->asTime(time());
      $early = date('08:00:00');
      //$last = date('11:00:59');
      $last = date('23:00:59');

      if ($early <= $timenow && $last >= $timenow)
      {
        echo "<tr>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "<td><center><strong> Early Discount (RM): </strong></td>";
          $discountamount = CartController::actionRoundoff1decimal($did['Orders_Subtotal']) * 0.2; ?>
          <td align=right> <font id="delivery"><?php echo CartController::actionRoundoff1decimal($discountamount); ?></font></td><?php
        echo "</tr>";

        $did['Orders_TotalPrice'] = CartController::actionRoundoff1decimal(CartController::actionRoundoff1decimal($did['Orders_Subtotal']) - CartController::actionRoundoff1decimal($discountamount) + CartController::actionRoundoff1decimal($did['Orders_DeliveryCharge']));
      }

      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td><center><strong> Total (RM): </strong></td>";?>
        <?php $form = ActiveForm::begin(); ?>
        <td align=right> <font id="total"><?php echo CartController::actionRoundoff1decimal($did['Orders_TotalPrice']); ?></font></td><?php
      echo "</tr>";
      echo "<tr>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";?>
        <td id ="extend"> </td>
        <td  id ="label" style="display: none"><strong> Discount Code: </strong></td>

        <td><div> <input id ="input" style="display: none"></div></td>

        <td style="display: none" id="apply"><div ><a onclick="discount()"><font color="blue">Apply</font></a></div></td>
        <td id ="hide2"><a onclick="showHidden()"><font color="blue">Have a coupon ? Click Me</font></a></td>
        <td id="reset" style="display : none"><a onclick="refresh()"><font color="blue">Reset Coupon</font></a></td>
        
          <?php
      echo "</tr>";
      echo "<tr>";
        echo "<td>". Html::a('Back',Yii::$app->request->referrer,['class' => 'btn btn-primary']) ."</td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
        echo "<td> </td>";
       ?>
        <?= $form->field($did, 'Orders_TotalPrice')->hiddenInput()->label('') ?>
       <?php
        echo "<td align="."right>".Html::submitButton('Checkout', ['class' => 'btn btn-primary', 'name' => 'newrestaurant-button'])."</td>";    
      echo "</tr>";
      ActiveForm::end(); ?>
    </table>
     
  </div>
</div>

	<?php
		}
		else
		{
		?>
		<div class="container" style="margin-top:2%;">
    		<div class="row">
        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				 <div> <?php echo Html::img('@web/imageLocation/Img/empty_cart.png', ['class' => 'img-responsivecol-lg-12 col-md-12 col-sm-12 col-xs-12']); ?>
		</div>
				
				</div>
			</div>
		</div>
		<?php
		}
		?>
<script >
  function showHidden()
  {
      document.getElementById("label").style.display ='block';
      document.getElementById("input").style.display ='block';
      document.getElementById("apply").style.display ='block';
      document.getElementById("hide2").style.display ='none';
      document.getElementById("extend").style.display ='none';
  }

  function discount()
  { 
    $.ajax({
   url :"index.php?r=cart/getdiscount",
   type: "get",
   data :{
        dis: document.getElementById("input").value,
   },
   success: function (data) {
      var obj = JSON.parse(data);
       if (obj != 0 ) 
      {
        switch(obj['discount_item']) 
        {
          case 7:
            if (obj['discount_type'] >=1 && obj['discount_type'] <=3) 
            {
              document.getElementById("subtotal").innerHTML = parseInt(document.getElementById("subtotal").innerHTML) *( (100 - obj['discount']) /100);
              document.getElementById("total").innerHTML = parseInt(document.getElementById("subtotal").innerHTML) + parseInt(document.getElementById("delivery").innerHTML); 
            }
            else if (obj['discount_type'] >=4 && obj['discount_type'] <=6) 
            {
              document.getElementById("subtotal").innerHTML = parseInt(document.getElementById("subtotal").innerHTML) - obj['discount'];
              document.getElementById("total").innerHTML = parseInt(document.getElementById("subtotal").innerHTML) + parseInt(document.getElementById("delivery").innerHTML);  
            }
           break;

          case 8:
            if (obj['discount_type'] >=1 && obj['discount_type'] <=3) 
            {
              document.getElementById("delivery").innerHTML = parseInt(document.getElementById("delivery").innerHTML) *( (100 - obj['discount']) /100);
              document.getElementById("total").innerHTML = parseInt(document.getElementById("subtotal").innerHTML) + parseInt(document.getElementById("delivery").innerHTML); 
            }
            else if (obj['discount_type'] >=4 && obj['discount_type'] <=6) 
            {
              document.getElementById("delivery").innerHTML = parseInt(document.getElementById("delivery").innerHTML) - obj['discount'];
              document.getElementById("total").innerHTML = parseInt(document.getElementById("subtotal").innerHTML) + parseInt(document.getElementById("delivery").innerHTML); 
            }
          break;

          case 9:
            if (obj['discount_type'] >=1 && obj['discount_type'] <=3) 
            {
              document.getElementById("total").innerHTML = parseInt(document.getElementById("total").innerHTML) *( (100 - obj['discount']) /100); 
            }
            else if (obj['discount_type'] >=4 && obj['discount_type'] <=6) 
            {
              document.getElementById("total").innerHTML = parseInt(document.getElementById("total").innerHTML) - obj['discount']; 
            }
          break;

           default:
            break;
        }
        document.getElementById("label").style.display ='none';
        document.getElementById("input").style.display ='none';
        document.getElementById("apply").style.display ='none';
        document.getElementById("hide2").style.display ='none';
        document.getElementById("extend").style.display ='block';
        document.getElementById("reset").style.display ='block';
        document.getElementById("orders-orders_totalprice").value = obj['code'];
      }

      else if (obj ==0) 
      {
        alert("No coupon found or coupon expired! Please check your account > eVoucher");
      }
   },
   error: function (request, status, error) {
    //alert(request.responseText);
   }

   });
  }

  function refresh()
  {
    location.reload();
  }

</script>