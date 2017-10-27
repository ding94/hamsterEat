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
  <div class="tab-content col-md-8 col-md-offset-2"  style="display: inline-block;" id="cart">

    <table class="table table-hover">
	<h1>Cart</h1>
	
	<thead>
	<tr>
	<th></th>
        <th>Food</th>
       <th>Unit Price (RM)</th>
        <th>Quantity</th>
        <th>LineTotal (RM)</th>
         <th></th>
		</tr>
    </thead>
	<tbody>
	<tr>
	<?php
      foreach ($cartitems as $k => $cartitem) :
        $fooddetails = Food::find()->where('Food_ID = :fid',[':fid'=>$cartitem['Food_ID']])->one();

        echo "<tr>";
        ?>
        <td> <?php echo Html::img('@web/imageLocation/foodImg/'.$fooddetails['PicPath'], ['class' => 'img-responsive','style'=>'height:60px; width:90px; margin:auto;']);?></td>
		<td> <?php 
		echo "<strong>"; ?>
		<p><a href="<?php echo yii\helpers\Url::to(['Restaurant/default/restaurant-details','rid'=>$fooddetails['Restaurant_ID']]) ?>" target="_blank"><?php echo $fooddetails['Name'] ?></a></p>
		<?php echo "</strong>";
		echo "<br>";
		 $selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$cartitem['Order_ID']])->all();
		foreach ($selections as $selections) :
          $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();
          $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
		  $unitprice = $selectionname['Price'] + $fooddetails['Price'];
          if (!is_null($selectionname['ID']))
          {
            echo $selectiontype['TypeName'].': &nbsp;'.$selectionname['Name'];
            echo "<br>";
          }
        endforeach;
		echo $cartitem['OrderItem_Remark'];?></td>
		<td><?php echo CartController::actionRoundoff1decimal($cartitem['OrderItem_SelectionTotal'] + $fooddetails['Price']);?></td>
        <td><?php echo $cartitem['OrderItem_Quantity'];?></td>
		<td><?php echo CartController::actionRoundoff1decimal($cartitem['OrderItem_LineTotal']); ?></td>
     
	   <td><?php echo Html::a('', ['delete','oid'=>$cartitem['Order_ID']], ['class'=>'btn btn-danger fa fa-trash','data-confirm'=>'Are you sure you want to remove from cart?']);  endforeach;$did = Orders::find()->where('Delivery_ID = :did',[':did'=>$did])->one();?></td>
	   </tr>
	   
	</tbody>
	</table>
	
</div>

 </div>
 <div class="container">
   <div class="tab-content col-md-5 col-md-offset-5" >

  <table class="table" style="float:right">
	<tbody>
                  <tr>
                    <td><b>Subtotal (RM):</td>
                    <td id="subtotal"><?php echo CartController::actionRoundoff1decimal($did['Orders_Subtotal']); ?></td>
					<td></td>
                  </tr>
				  <tr>
                    <td><b>Delivery Charge (RM):</td>
                    <td id="delivery"><?php echo CartController::actionRoundoff1decimal($did['Orders_DeliveryCharge']); ?></td>
						<td></td>
                  </tr>
				  <tr>
                    <td><b>Early Discount (RM):</td>
                    <td><?php 
	$timenow = Yii::$app->formatter->asTime(time());
    $early = date('08:00:00');
    $last = date('23:00:59');

      if ($early <= $timenow && $last >= $timenow)
      {
		$discountamount = CartController::actionRoundoff1decimal($did['Orders_Subtotal']) * 0.2; 
		echo "-".CartController::actionRoundoff1decimal($discountamount);
	    $did['Orders_TotalPrice'] = CartController::actionRoundoff1decimal(CartController::actionRoundoff1decimal($did['Orders_Subtotal']) - CartController::actionRoundoff1decimal($discountamount) + CartController::actionRoundoff1decimal($did['Orders_DeliveryCharge']));
		
		}?>
	  </td>
	  	<td></td>
                  </tr>
				  <tr>
                    <td><b>Total (RM): </td>
					<?php $form = ActiveForm::begin(); ?>
                    <td id="total"><?php echo CartController::actionRoundoff1decimal($did['Orders_TotalPrice']); ?></td>
						<td></td>
                  </tr>
				 
				   <tr>
                    
        <td  id ="label" style="display: none"><strong> Discount Code: </strong></td>
        <td><div> <input id ="input" style="display: none"></div></td>
        <td id ="hide2"><a onclick="showHidden()"><font color="blue">Have a coupon ? Click Me</font></a></td>
		<td style="display: none" id="apply"><div ><a onclick="discount()"><font color="blue">Apply</font></a></div></td>
        <td id="reset" style="display : none"><a onclick="refresh()"><font color="blue">Reset Coupon</font></a></td>
        </tr>
	</tbody>
   </table>
    <?= $form->field($did, 'Orders_TotalPrice')->hiddenInput()->label('') ?>
  <?php echo Html::a('Back',Yii::$app->request->referrer,['class' => 'btn btn-primary']) ;?>
  <?php echo Html::submitButton('Checkout', ['class' => 'btn btn-primary', 'name' => 'newrestaurant-button']);   ?>
  <?php ActiveForm::end(); ?>
    
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
	</div>
<script >
  function showHidden()
  {
      document.getElementById("label").style.display ='block';
      document.getElementById("input").style.display ='block';
      document.getElementById("apply").style.display ='block';
      document.getElementById("hide2").style.display ='none';
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
              document.getElementById("subtotal").innerHTML = (parseFloat(document.getElementById("subtotal").innerHTML) *( (100 - obj['discount']) /100)).toFixed(2);
              document.getElementById("total").innerHTML = (parseFloat(document.getElementById("subtotal").innerHTML) + parseFloat(document.getElementById("delivery").innerHTML)).toFixed(2); 
            }
            else if (obj['discount_type'] >=4 && obj['discount_type'] <=6) 
            {
              document.getElementById("subtotal").innerHTML = (parseFloat(document.getElementById("subtotal").innerHTML) - obj['discount']).toFixed(2);
              document.getElementById("total").innerHTML = (parseFloat(document.getElementById("subtotal").innerHTML) + parseFloat(document.getElementById("delivery").innerHTML)).toFixed(2);  
            }
           break;

          case 8:
            if (obj['discount_type'] >=1 && obj['discount_type'] <=3) 
            {
              document.getElementById("delivery").innerHTML = (parseFloat(document.getElementById("delivery").innerHTML) *( (100 - obj['discount']) /100)).toFixed(2);
              document.getElementById("total").innerHTML = (parseFloat(document.getElementById("subtotal").innerHTML) + parseFloat(document.getElementById("delivery").innerHTML)).toFixed(2); 
            }
            else if (obj['discount_type'] >=4 && obj['discount_type'] <=6) 
            {
              document.getElementById("delivery").innerHTML = (parseFloat(document.getElementById("delivery").innerHTML) - obj['discount']).toFixed(2);
              document.getElementById("total").innerHTML = (parseFloat(document.getElementById("subtotal").innerHTML) + parseFloat(document.getElementById("delivery").innerHTML)).toFixed(2); 
            }
          break;

          case 9:
            if (obj['discount_type'] >=1 && obj['discount_type'] <=3) 
            {
              document.getElementById("total").innerHTML = (parseFloat(document.getElementById("total").innerHTML) *( (100 - obj['discount']) /100)).toFixed(2); 
            }
            else if (obj['discount_type'] >=4 && obj['discount_type'] <=6) 
            {
              document.getElementById("total").innerHTML = (parseFloat(document.getElementById("total").innerHTML) - obj['discount']).toFixed(2); 
            }
          break;

           default:
            break;
        }
        document.getElementById("label").style.display ='none';
        document.getElementById("input").style.display ='none';
        document.getElementById("apply").style.display ='none';
        document.getElementById("reset").style.display ='block';
        document.getElementById("orders-orders_totalprice").value = obj['code'];
      }

      else if (obj ==0) 
      {
        alert("No coupon found or coupon expired! Please check your account > Discount Codes");
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
 
