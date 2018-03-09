<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\controllers\CartController;
use frontend\assets\CartAsset;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = Yii::t('cart','My Cart');
CartAsset::register($this);

$url = Url::to(['cart/delete']);
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
       <span class="title"><?=Yii::t('common','Cart');?></span>
     </div>
     <span class="bar"></span>
     <div class="circle deactive">
       <span class="label"><i class="fa fa-cart-arrow-down"></i></span>
       <span class="title"><?=Yii::t('common','Checkout');?></span>
     </div>
     <span class="bar"></span>
     <div class="circle deactive">
       <span class="label"><i class="fa fa-credit-card"></i></span>
       <span class="title"><?=Yii::t('common','Payment');?></span>
     </div>
   </div> 
  </div>

  <div class ="container">
	  <div class="htitlemargintop"></div>
    <div class="htitleline"></div>
    <div class="hometitlefp">
      <h1 style="text-shadow: 1.5px 1.5px #ffda00;">
        <center><?=Yii::t('common','Cart');?></center>
      </h1>
    </div>
    <div class="htitlemarginbottom"> 
    </div>
      <?php echo Html::a(Yii::t('cart','Continue Shopping'),Yii::$app->request->referrer,['class' => 'raised-btn btn-b']) ;?>
	</div>
   <?php $form = ActiveForm::begin(['action' =>['checkout/process'],'method' => 'post']); ?>
	<div class="container">
    <?php foreach($cart as$i=> $single) :
      $disable = $single->status == 0 ? true:false;
    ?> 
			<section class="cart <?php echo $disable ? "disable-cart" : ""?>" data-status=<?php echo $single->status?>>
        <?php if($disable):?>
        <div class="disable-overlay"><div>Not Available<a class="fa fa-trash delete" href="#" data-id=<?php echo $i?> data-url=<?php echo $url?> ></a></div></div>
        <article class="product disable-opacity">
        <?php else : ?>
			  <article class="product">
        <?php 
          endif ;
          echo Html::hiddenInput('id',$single['id'],['class'=>$i."-id"]);
          echo Html::hiddenInput('cid[]',$single['id'],['disabled'=>$disable == 0 ? true : false])
          ?> 
				  <header>
					  <a class="remove">
              <img src=<?php echo $single->food->singleImg ?> alt="" class="img-responsive"> 
              <h3> 
                <a class="remove delete" href="#" data-id=<?php echo $i?> data-url=<?echo $url><?=Yii::t('common','Remove');?></a>
  				    </h3>
				    </a>
				</header> 
	 
        <div class="content">				
      		<h1>
            <?php echo Html::a(Yii::t('cart',$single['food']['cookieName']),['Restaurant/default/restaurant-details','rid'=> $single['food']['Restaurant_ID']],['target'=>"_blank"])?>
          </h1>
          <div class="relative">
              <?=Yii::t('cart','Food Selection');?>
            <i class="fa fa-info-circle"> <span class="i-detail i-selection" > 
            <?php foreach($single['groupselection'] as $name=>$selection):?>
              <?php $text = implode( ", ", $selection );?>
                 <?php echo $text?>
            <?php endforeach;?></span></i>&nbsp; 
          </div>
      		<?php if(!empty($single['remark'])): ?>
            <div class="relative upper-trash" style="color:#fc7171;"><?=Yii::t('common','Remarks');?>
      				<i class="fa fa-info-circle"> <span class="i-detail i-selection" ><?php echo $single['remark'];?><span >  </i>
            </div>
      			<?php endif; ?>
          <a id="d" class="fa fa-trash delete" href="#" data-id=<?php echo $i?> data-url=<?php echo $url;?>></a>
      	</div>	
      	<footer class="content">
          <?php $url = Url::to(['cart/quantity'])?>
      		<span class="qt-minus plusMinus" data-id=<?php echo $i?> data-url=<?php echo $url?>>-</span>
      		<span class="qt" id="qt"> <?php echo $single['quantity'];?></span>
      		<span class="qt-plus plusMinus" data-id=<?php echo $i?> data-url=<?php echo $url?>>+</span>
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
        <?php echo Html::submitButton(Yii::t('common','Checkout'), ['class' => 'raised-btn main-btn checkout-btn']);?>
     
  </div>
  <?php endforeach ;?>
  <?php echo Html::hiddenInput('totalCart' , $count);?>
   <?php ActiveForm::end(); ?>
<?php endif ;?>
</div>