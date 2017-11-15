<?php
use yii\helpers\Html;
use common\models\food\Food;
use common\models\Orderitemselection;
use common\models\food\Foodselectiontype;
Use common\models\food\Foodselection;
Use common\models\Orders;
use yii\bootstrap\ActiveForm;
use frontend\controllers\CartController;
use frontend\assets\CartAsset;
use yii\bootstrap\Modal;

$this->title = "My Cart";
CartAsset::register($this);

Modal::begin([
      'header' => '<h2 class="modal-title">Please choose delivery place</h2>',
      'id'     => 'add-session-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]);
Modal::end();

?>
<?php
			if($cartitems == true)
			{
		?>
<div class="container">
  
  <input class="did" type="hidden" value=<?php echo $did ?>></input>
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
		 <!-- <td class="quantity" id="qt"><span class="fa fa-angle-left angle"></span><?php echo $cartitem['OrderItem_Quantity'];?><span class="fa fa-angle-right angle"></span></td>-->

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
                    <td id="early"><?php 
	$timenow = Yii::$app->formatter->asTime(time());
    $early = date('08:00:00');
    $last = date('23:00:59');

      if ($early <= $timenow && $last >= $timenow)
      {
		$discountamount = CartController::actionRoundoff1decimal($did['Orders_Subtotal']) * 0.2; 
		echo "-".CartController::actionRoundoff1decimal($discountamount);
	    $did['Orders_TotalPrice'] = CartController::actionRoundoff1decimal(CartController::actionRoundoff1decimal($did['Orders_Subtotal']) - CartController::actionRoundoff1decimal($discountamount) + CartController::actionRoundoff1decimal($did['Orders_DeliveryCharge']));
		}
    else{ echo 0.00; }?>
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

  <?php 
    if (empty(Yii::$app->session['area']) || empty(Yii::$app->session['postcode'])) {
      echo Html::a('Checkout',['/cart/addsession'],['class' => 'btn btn-primary','data-toggle'=>'modal','data-target'=>'#add-session-modal']);
    }
    else{
      echo Html::submitButton('Checkout', ['class' => 'btn btn-primary', 'name' => 'newrestaurant-button']);
    }
  ?>
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

 <!-- js for quantity 
<script>
const arrows = document.querySelector('.quantity').querySelectorAll('.fa'); 

const handleChange = (elem)=>{
  const qt = document.querySelector('#qt');
  const total = document.querySelector('#price');
  let value = parseInt(qt.innerText);
  let classArr = Array.from(elem.classList);
  if(/right/gi.test(classArr)) {
    if(value!=9) value++;
    else alert('Watch out! We\'ve got a badass over here!');
  }
  else {
    if(value!=1) value--;
    else alert('Watch out! We\'ve got a badass over here!');
  }
  qt.innerText = value;
  total.innerText = '$'+(value*320); 
}

const product = document.querySelector('.product');

const moveBox = (val)=>{
  product.style.mozTransform =
  product.style.msTransform =
  product.style.webkitTransform =
  product.style.transform = 'translateX('+val+'px)';
}

const back = document.querySelector('.back');

const spring = new rebound.SpringSystem();

let animation = spring.createSpring(60,3);

animation.addListener({
  onSpringUpdate(spring){
    let current = spring.getCurrentValue();
    if(current > 1) spring.setEndValue(0);
    let val =  rebound.MathUtil.mapValueInRange(current,0,1,0,20);
    moveBox(val);
  }
})

back.addEventListener('click',()=>{
  animation.setEndValue(1);
});


const arrArr = Array.from(arrows);

arrArr.forEach(elem=>{
  elem.addEventListener('click',()=>{
    handleChange(elem);
  })
})
</script>-->