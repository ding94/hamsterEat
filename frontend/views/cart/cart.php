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

<?php if(empty($groupCart)): ?>
  <div class="container" style="margin-top:2%;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div> <?php echo Html::img('@web/imageLocation/Img/empty_cart.png', ['class' => 'img-responsivecol-lg-12 col-md-12 col-sm-12 col-xs-12']); ?>
              </div>
        
        </div>
    </div>
  </div>
<?php else :?>
  <?php foreach($groupCart as $index=>$cart): ?>
    <?php $total = 0 ; $earlyDiscount = 0;?>
      <div class="container">
        <h1>Cart</h1>
        <div class="tab-content col-md-8 col-md-offset-2"  style="display: inline-block;" id="cart">
          <table class="table table-hover">
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
              <?php foreach($cart as $single) :?> 
              <tr>
                <td>
                  <?php echo Html::img('@web/imageLocation/foodImg/'.$single['food']['PicPath'], ['class' => 'img-responsive','style'=>'height:60px; width:90px; margin:auto;']);?>  
                </td>
                <td>
                  <?php echo Html::a($single['food']['Name'],['Restaurant/default/restaurant-details','rid'=> $single['food']['Restaurant_ID']],['target'=>"_blank"])?>
                  <br>
                    <?php foreach($single['groupselection'] as $name=>$selection):?>
                      <?php $text = implode( ", ", $selection );?>
                      <p><?php echo $name .': &nbsp;'. $text?></p>
                    <?php endforeach;?>
                  <br>
                  <p><?php echo $single['remark'];?></p>
                </td>
                <td><?php echo $single['price'];?></td>
                <td><?php echo $single['quantity'];?></td>
                <td><?php echo $single['quantity'] * $single['price'];?></td>
                <?php $total += $single['quantity'] * $single['price']?>
                <td>
                  <?php echo Html::a('', ['delete','id'=>$single['id']], ['class'=>'btn btn-danger fa fa-trash','data-confirm'=>'Are you sure you want to remove from cart?']);  ?> 
                </td>
              </tr>
              <?php endforeach ;?>
            </tbody>
          </table>
        </div>
        <div class="container">
          <div class="tab-content col-md-5 col-md-offset-5" >
            <table class="table" style="float:right">
              <tr>
                <?php $total = CartController::actionRoundoff1decimal($total) ?>
                <td><b>Subtotal (RM):</td>
                <td id="subtotal"><?php echo $total ; ?></td>
                <td></td>
              </tr>
              <tr>
                <td><b>Delivery Charge (RM):</td>
                <td id="delivery">5.00</td>
                <td></td>
              </tr>
              <?php if($time['early'] <= $time['now'] && $time['late'] >= $time['now']):?>
              <tr>
                <?php $earlyDiscount = CartController::actionRoundoff1decimal($total *0.2)?>
                <td><b>Early Discount (RM):</td>
                <td>-<?php echo $earlyDiscount?></td>
                <td></td
              </tr>
              <?php endif ;?>
              <tr>
                <?php $finalPrice = $total - $earlyDiscount + 5 ;?>
                <td><b>Total (RM): </td>
                <td id="total"><?php echo CartController::actionRoundoff1decimal($finalPrice); ?></td>
                <td></td>
              </tr>
              <tr>
                <td  id ="label" style="display: none"><strong> Discount Code: </strong></td>
                <td><div> <input id ="input" style="display: none"></div></td>
                <td id ="hide2"><a onclick="showHidden()"><font color="blue">Have a coupon ? Click Me</font></a></td>
                <td style="display: none" id="apply"><div ><a onclick="discount()"><font color="blue">Apply</font></a></div></td>
                <td id="reset" style="display : none"><a onclick="refresh()"><font color="blue">Reset Coupon</font></a></td>
              </tr>
            </table>
              <?php echo Html::a('Back',Yii::$app->request->referrer,['class' => 'btn btn-primary']) ;?>
              <?php $form = ActiveForm::begin(['action' =>['checkout/index'],'method' => 'get']); ?>
                <?php echo Html::hiddenInput('area', $index);?>
             
                <?php echo Html::submitButton('Checkout', ['class' => 'btn btn-primary']);?>
              <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
  <?php endforeach ;?>
<?php endif ;?>

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