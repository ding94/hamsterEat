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
use yii\helpers\Url;

$this->title = "My Cart";
CartAsset::register($this);

Modal::begin([
      'header' => '<h2 class="modal-title">Please choose delivery place</h2>',
      'id'     => 'add-modal',
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
   <div class="checkout-progress-bar">
     <div class="circle active">
       <span class="label"><i class="fa fa-shopping-cart"></i></span>
       <span class="title">Cart</span>
     </div>
     <span class="bar"></span>
     <div class="circle deactive">
       <span class="label"><i class="fa fa-cart-arrow-down"></i></span>
       <span class="title">Checkout</span>
     </div>
     <span class="bar"></span>
     <div class="circle deactive">
       <span class="label"><i class="fa fa-credit-card"></i></span>
       <span class="title">Payment</span>
     </div>
   </div> 
  </div>

  <div class ="container">
	  <div class="htitlemargintop"></div><div class="htitleline"></div><div class="hometitlefp"><h1 style="text-shadow: 1.5px 1.5px #ffda00;"><center>Cart</center></h1></div><div class="htitlemarginbottom"> 
    </div>
      <?php echo Html::a('Continue Shopping',Yii::$app->request->referrer,['class' => 'btn btn-b']) ;?>
	</div>
	<div class="container">
    <?php foreach($cart as $single) :?> 
			<section id="cart"> 
			  <article class="product">
				  <header>
					  <a class="remove">
              <?php echo Html::img('@web/imageLocation/foodImg/'.$single['food']['PicPath'], ['class' => 'img-responsive']);?>  
              <h3> 
                <?php echo Html::a('Remove', ['delete','id'=>$single['id']], ['class'=>'remove ','data-confirm'=>'Are you sure you want to remove from cart?']);?> 
  				    </h3>
				    </a>
				</header> 
	 
        <div class="content">				
      		<h1>
            <?php echo Html::a($single['food']['Name'],['Restaurant/default/restaurant-details','rid'=> $single['food']['Restaurant_ID']],['target'=>"_blank"])?>
          </h1>
      		<?php foreach($single['groupselection'] as $name=>$selection):?>
            <?php $text = implode( ", ", $selection );?>
              <span style="color:#a38b01;">   <?php echo $text?></span>
            <?php endforeach;?>&nbsp;	
      			<?php if(!empty($single['remark'])): ?>
      				<span style="color:#fc7171;">  <?php echo '|'.' &nbsp;'.$single['remark'];?></span>
      			<?php endif; ?>
      			<?php echo Html::a('', ['delete','id'=>$single['id']], ['class'=>'fa fa-trash','id'=>'d','data-confirm'=>'Are you sure you want to remove from cart?']);  ?> 
      	</div>	
      	<footer class="content">
          <?php echo Html::hiddenInput('id',$single['id'])?>
      		<span class="qt-minus">-</span>
      		<span class="qt" id="qt"> <?php echo $single['quantity'];?></span>
      		<span class="qt-plus">+</span>
          <h2 class="full-price">RM
      			<?php echo  $single['price'] * $single['quantity'];?>
      			<?php $total += $single['quantity'] * $single['price']?>
      		</h2>
      	</footer>
			</article>   
    <?php endforeach ;?>
  </div> 
  <iframe id="iframe" src=<?php echo Url::toRoute(['cart/totalcart','area'=>$index])?>></iframe>
   <?php $form = ActiveForm::begin(['action' =>['checkout/index'],'method' => 'get']); ?>
        <?php echo Html::hiddenInput('area', $index);?>
        <?php echo Html::hiddenInput('code', '');?>
        <?php echo Html::submitButton('Checkout', ['class' => 'btn btn-b']);?>
      <?php ActiveForm::end(); ?>
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
