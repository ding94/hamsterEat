<?php
use yii\helpers\Html;
use common\models\food\Food;
use common\models\food\Foodselectiontype;
Use common\models\food\Foodselection;
use yii\bootstrap\ActiveForm;
use frontend\controllers\CartController;
use frontend\assets\CartAsset;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = Yii::t('cart','My Cart');
CartAsset::register($this);

?>

<?php if(empty($groupCart)): ?>
  <div class="container" style="margin-top:2%;">
    <div class="row">
        <div class="col-xs-12">
          <div> <?php echo Html::img('@web/imageLocation/Img/empty_cart.png', ['class' => 'img-responsive col-xs-12']); ?>
          </div>
        </div>
    </div>
  </div>
<?php else :?>
<div id="outer-cart">
  <?php $count = 0;?>
  <?php foreach($groupCart as $index=>$cart): ?>
  <?php $earlyDiscount = 0;
        $count += count($cart);
  ?>
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
      <?php echo Html::a('Continue Shopping',Yii::$app->request->referrer,['class' => 'raised-btn btn-b']) ;?>
	</div>
   <?php $form = ActiveForm::begin(['action' =>['checkout/index'],'method' => 'post']); ?>
	<div class="container">
    <?php foreach($cart as $single) :?> 
			<section class="cart">
			  <article class="product">
          <?php echo Html::hiddenInput('id',$single['id'])?> 
          <?php echo Html::hiddenInput('cid[]',$single['id'])?> 
				  <header>
					  <a class="remove">
              <img src=<?php echo $single->food->singleImg ?> alt="" class="img-responsive"> 
              <h3> 
                <a class="remove delete" href="#">Remove</a>
  				    </h3>
				    </a>
				</header> 
	 
        <div class="content">				
      		<h1>
            <?php echo Html::a($single['food']['Name'],['Restaurant/default/restaurant-details','rid'=> $single['food']['Restaurant_ID']],['target'=>"_blank"])?>
          </h1>
          <div class="relative">
            Food Selection
            <i class="fa fa-info-circle"> <span class="i-detail i-selection" > 
            <?php foreach($single['groupselection'] as $name=>$selection):?>
              <?php $text = implode( ", ", $selection );?>
                 <?php echo $text?>
            <?php endforeach;?></span></i>&nbsp; 
          </div>
      		<?php if(!empty($single['remark'])): ?>
            <div class="relative upper-trash" style="color:#fc7171;">Remark
      				<i class="fa fa-info-circle"> <span class="i-detail i-selection" ><?php echo $single['remark'];?><span >  </i>
            </div>
      			<?php endif; ?>
          <a id="d" class="fa fa-trash delete" href="#"></a>
      	</div>	
      	<footer class="content">
      		<span class="qt-minus plusMinus">-</span>
      		<span class="qt" id="qt"> <?php echo $single['quantity'];?></span>
      		<span class="qt-plus plusMinus">+</span>
          <h2 class="full-price">RM
      			<?php echo  CartController::actionRoundoff1decimal($single['price'] * $single['quantity']);?>
      		</h2>
      	</footer>
			</article>  
    </section> 
    <?php endforeach ;?>
  </div> 
  <iframe id="iframe" src=<?php echo Url::toRoute(['cart/totalcart','area'=>$index])?>></iframe>
  <div class="container">
        <?php echo Html::hiddenInput('area', $index);?>
        <?php echo Html::hiddenInput('code', '');?>
        <?php echo Html::submitButton('Checkout', ['class' => 'raised-btn main-btn checkout-btn']);?>
     
  </div>
  <?php endforeach ;?>
  <?php echo Html::hiddenInput('totalCart' , $count);?>
   <?php ActiveForm::end(); ?>
<?php endif ;?>
</div>