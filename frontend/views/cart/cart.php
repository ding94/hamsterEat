<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\controllers\CartController;
use frontend\assets\CartAsset;
use yii\bootstrap\Modal;
use yii\helpers\Url;

$this->title = Yii::t('cart','My Cart');
CartAsset::register($this);
$deleteurl = Url::to(['cart/delete']);
$quantityurl = Url::to(['cart/quantity']);
$cart_status = 0;

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
    <?php foreach($cart as $i=> $single) :
      $disable = $single->status == 0 ? true:false;
      $cart_status += $single->status;

    ?> 
      <section id="cart-<?php echo $i?>" class="cart <?php echo $disable ? "disable-cart" : ""?>" data-status=<?php echo $single->status?>>
        <?php if($disable):?>
        <div class="disable-overlay"><div>Not Available<a class="fa fa-trash delete" href="#" data-id=<?php echo $i?> data-url=<?php echo $deleteurl?> ></a></div></div>
        <div class="cart-item-container disable-opacity">
        <?php else : ?>
        <div class="cart-item-container">
        <?php 
          endif ;
          echo Html::hiddenInput('id',$single['id'],['class'=>$i."-id"]);
          echo Html::hiddenInput('promotion[]',$single['promotion_enable'],['disabled'=>$disable]);
          echo Html::hiddenInput('cid[]',$single['id'],['disabled'=>$disable]);
          ?> 
          <div class="img">
            <a class="remove">
              <img src=<?php echo $single->food->singleImg ?> alt="" class="img-responsive">
              <h3> 
                <a class="remove delete" href="#" data-id=<?php echo $i?> data-url=<?php echo $deleteurl?><?=Yii::t('common','Remove');?></a>
              </h3>
            </a>
          </div>
          <div class="content">
            <div class="content-container">
              <h1>
                <?php echo Html::a(Yii::t('cart',$single['food']['cookieName']),['Restaurant/default/restaurant-details','rid'=> $single['food']['Restaurant_ID']],['target'=>"_blank"])?>
              </h1>
              <?php if(!empty($single->groupselection)):?>
                <div class="panel-group">
                  <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" href="#collapse<?php echo $i?>">
                      <h4 class="panel-title">
                        <a>
                          <?=Yii::t('cart','Food Selection Detail');?>
                          <i class="fa fa-info-circle"></i>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse<?php echo $i?>" class="panel-collapse collapse">
                      <div class="panel-body">
                      <?php 
                      foreach($single->groupselection as $name=>$selection):
                        $text = implode( ", ", $selection );
                        echo $name .":  ".$text ."</br>";
                      endforeach;
                      ?>
                      </div>
                    </div>
                  </div>
                </div>
            <?php endif;?>
            <?php if(!empty($single->nick)):?>
              <div class="panel-group">
                  <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" href="#nick<?php echo $i?>">
                      <h4 class="panel-title">
                        <a>
                          <?=Yii::t('cart','NickName Detail');?>
                          <i class="fa fa-info-circle"></i>
                        </a>
                      </h4>
                    </div>
                    <div id="nick<?php echo $i?>" class="panel-collapse collapse">
                      <div class="panel-body">
                      <button type="" class="btn btn-default btn-block">Add</button>
                      <br>
                      <?php foreach($single->nick as $k=>$nickname):?>
                      <div class="input-group">
                        <input type="text" class="form-control" value= <?= $nickname->nickname;?>><span class="input-group-btn"><button class="btn btn-default">Delete</button></span>
                      </div>
                      </br>
                      <?php endforeach;?>
                      </div>
                    </div>
                  </div>
                </div>
            <?php endif;?>
            <?php if(!empty($single['remark'])): ?>
            <div class="relative upper-trash" style="color:#fc7171;"><?=Yii::t('common','Remarks');?>
              <i class="fa fa-info-circle"> <span class="i-detail i-selection" ><?php echo $single['remark'];?><span >  </i>
            </div>
            <?php endif; ?>
              <a id="d" class="fa fa-trash delete" href="#" data-id=<?php echo $i?> data-url=<?php echo $deleteurl;?>></a>
            </div>
            <div class="footer-content-container">
              <span class="qt-minus plusMinus" data-id=<?php echo $i?> data-url=<?php echo $quantityurl?>>-</span>
              <span class="qt" id="qt"> <?php echo $single['quantity'];?></span>
              <span class="qt-plus plusMinus" data-id=<?php echo $i?> data-url=<?php echo $quantityurl?>>+</span>
              <h2 class="full-price">RM
                <?php echo  CartController::actionRoundoff1decimal($single['price'] * $single['quantity']);?>
              </h2>
            </div>
          </div>
        </div>
      </section>
    <?php endforeach ;?>
  </div> 
  <iframe id="iframe" src=<?php echo Url::toRoute(['cart/totalcart','area'=>$index])?>></iframe>
  <div class="container">
        <?php echo Html::hiddenInput('area', $index);?>
        <?php echo Html::hiddenInput('code', '');?>
        <?php if($cart_status >= 1){ ?>
        <?php echo Html::submitButton(Yii::t('common','Checkout'), ['class' => 'raised-btn main-btn checkout-btn']);?>
        <?php } else { ?>
        <div class="raised-btn main-btn checkout-btn disable-btn"><?php echo Yii::t('common','Checkout') ?></div>
        <?php } ?>
     
  </div>
  <?php endforeach ;?>
  <?php echo Html::hiddenInput('totalCart' , $count);?>
   <?php ActiveForm::end(); ?>
<?php endif ;?>
</div>