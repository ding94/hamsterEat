<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use frontend\controllers\CartController;
use kartik\widgets\TouchSpin;
use kartik\widgets\DatePicker;
use common\models\User;
use yii\helpers\Url;
use frontend\assets\StarsAsset;
use frontend\assets\FoodDetailsAsset;
use iutbay\yii2fontawesome\FontAwesome as FA;
$this->title =  Yii::t('food','Food Details');

StarsAsset::register($this);
FoodDetailsAsset::register($this);

date_default_timezone_set("Asia/Kuala_Lumpur");
?>

<div>
  <ul class="nav nav-tabs nav-justified food-details-tab">
    <li class="active"><a data-toggle="pill" href="#home"><?= Yii::t('food','Food Details') ?></a></li>
    <li ><a data-toggle="pill" href="#comments"><?= Yii::t('food','Comments') ?></a></li>
  </ul>
</div>
<div class="tab-content">
  <div id="home" class="tab-pane fade in active"><a name="home"></a>
  <div class="row">
  	<div class="tab-content col-md-12" id="fooddetails">
      <?php $form = ActiveForm::begin(['id' => 'a2cart']); ?>
          
  		<!--<table class="table-user-information" style="width:60%; margin:auto;">-->  
            <?php echo Html::hiddenInput('url',Url::to(['/cart/addto-cart','id'=>$fooddata->Food_ID]));?>       
             <br>
              <div class="foodname">
                <span><?php echo $fooddata->cookieName;?></span>
                <?php if($fooddata->promotion_enable == 1):?>
                <span class="food-limit-span">Promotion Available: <?php echo $fooddata->promotion_left ?></span>
                <?php else:?>
                <span class="food-limit-span">Available to Order: <?php echo $foodlimit->food_limit ?></span>
                <?php endif;?>
              </div>
             
              <?php $am = (time() < strtotime(date("Y/m/d 11:0:0")) || $fooddata->promotion_enable == 1);
                if($am) :
                  if($fooddata->promotion_enable == 0):
                    $discount = CartController::actionRoundoff1decimal($fooddata->Price*0.15);
                    $price = CartController::actionRoundoff1decimal($fooddata->Price);
                    $price=$price-$discount;
                  else :
                    $price = CartController::actionRoundoff1decimal($fooddata->promotion_price);
                  endif;
                else:
                  $price =CartController::actionRoundoff1decimal($fooddata->Price);
                endif;
                //$price = fooddata->prmotion_enable == 0 : ? $fooddata->Price *0.15;

              ?>
          <div class="foodprice" data-price="<?php echo $price?>">
            <?php if ($am == true):?>
              <span><strike><?php echo 'RM'.$fooddata->Price; ?></strike></span><span class='price'><?php echo 'RM'.number_format($price,2); ?></span>
            <?php else: ?>
              <span class="price"><?php echo 'RM'.$price; ?></span>
            <?php endif;?>
          </div>
            
        <br>
              <div class="description">
                   <!--<td>Food Description:</td>-->
                   <span><?php echo $fooddata->Description;?></span>
                   </div>
              <br>
                <div class="selection">

              <?php  
                $ftids = array();
               
                foreach($foodtype as $k=> $type) : 
                  $selection = Foodselection::find()->where('Type_ID = :ftid and status = 1',[':ftid' => $type['ID']])->orderBy(['Price' => SORT_ASC])->all();
                 
                  if(!empty($selection)):
                  $data = ArrayHelper::map($selection,'ID','typeprice');
                  $checkboxdata = ArrayHelper::map($selection,'ID','checkboxtypeprice');
                  if ($type['Min'] == 1 && $type ['Max'] < 2 ) {
                    ?>
                      <span class="selection-name"><?php echo $type['cookieName']; ?></span>
                      <span class="selection-warning">*<?= Yii::t('food','Please Select only 1 item.') ?></span>
                        <?= $form->field($cartSelection,'selectionid['.$type['ID'].']', ['enableClientValidation' => false])->radioList($data,[
                                  'item' => function($index, $label, $name, $checked, $value) {

                                      $return = '<div class="radio">';
                                      $return .= '<input id="'. $value .'" class="radio-custom price" type="radio" name="' . $name . '" value="' . $value . '" >';
                                      $return .= '<label for="'. $value .'" class="food-detail-label">';
                                      $return .= $label;
                                      $return .= '</label>';
                                      $return .= '</div>';
                                      
                                      return $return;
                                  }
                              ])->label(false); ?>
                      
                    
                <?php } else if ($type['Min'] == 0){ ?>
                    
                      
                        <span class="selection-name"><?php echo $type['cookieName']; ?></span>
                       
                        <span class="selection-warning">
                          *<?= Yii::t('food','Select at most').' '.$type ['Max'].Yii::t('food','items.'); ?>
                        </span>
                      
                     
                        <?= $form->field($cartSelection,'selectionid['.$type['ID'].']', ['enableClientValidation' => false])->checkboxlist($checkboxdata,[
                                  'item' => function($index, $label, $name, $checked, $value) {

                                      $return = '<div class="checkbox">';
                                      $return .= '<input id="'. $value .'" class="checkbox-custom price" type="checkbox" name="' . $name . '" value="' . $value . '" >';
                                      $return .= '<label for="'. $value .'" class="food-detail-label">';
                                      $return .= $label;
                                      $return .= '</label>';
                                      $return .= '</div>';

                                      return $return;
                                  }
                              ])->label(false);?>
                    
                  
                <?php } else { ?>
                   
                        <span class="selection-name"><?php echo $type['cookieName']; ?></span>
                   
                        <span class="selection-warning">
                          *<?= Yii::t('food','Select') ?> <?= Yii::t('food','at least') ?> <?php echo $type['Min']; ?> <?= Yii::t('food','item') ?> <?= Yii::t('food','and') ?> <?= Yii::t('food','at most') ?> <?php echo $type ['Max']; ?> <?= Yii::t('food','items.') ?>
                        </span>
                     
                    
                        <?= $form->field($cartSelection,'selectionid['.$type['ID'].']', ['enableClientValidation' => false])->checkboxlist($checkboxdata,[
                                  'item' => function($index, $label, $name, $checked, $value) {

                                      $return = '<div class="checkbox">';
                                      $return .= '<input id="'. $value .'" class="checkbox-custom price" type="checkbox" name="' . $name . '" value="' . $value . '" >';
                                      $return .= '<label for="'. $value .'" class="food-detail-label">';
                                      $return .= $label;
                                      $return .= '</label>';
                                      $return .= '</div>';

                                      return $return;
                                  }
                              ])->label(false);?>
                   
                    
                <?php }
                  else:
                      echo $form->field($cartSelection,'selectionid['.$type['ID'].']', ['enableClientValidation' => false])->hiddenInput()->label(false);
                  endif;
                  endforeach; 
                ?>
                 </div>
                <!-- Disable temporary -->
                <!--  <?= $form->field($cart, 'remark',['enableClientValidation' => false])->label(Yii::t('common','Remarks')); ?> -->
               
                <?= $form->field($cart, 'quantity',['options'=>['class'=>'quantity']],['enableClientValidation' => false])->widget(TouchSpin::classname(), [
                    'options' => [
                        'style'=>'height:40px;text-align:center',
                        'class'=>'price'
                    ],
                    'pluginOptions' => [
                        'min' => 1,
                        'max'=>100,
                        'initval' => 1,
                        'buttonup_class' => 'btn btn-primary plus-btn', 
                        'buttondown_class' => 'btn btn-primary minus-btn', 
                        'buttonup_txt' => '<i class="fa fa-plus"></i>', 
                        'buttondown_txt' => '<i class="fa fa-minus"></i>'
                    ],
                ])->label(false); ?> 
              <div>
                <?= Html::submitButton(Yii::t('food','Add to cart').'<span class="total-price">'. CartController::actionRoundoff1decimal($price) .'</span>', ['class' => 'raised-btn addtocart-btn', 'name' => 'addtocart']) ?>

       
             </div>
           
            <?php ActiveForm::end(); ?>
        </div>
  </div>
</div>
<div id="comments" class="tab-pane fade"><a name=""></a>
<?php
$i = 1;
foreach ($comments as $comments) :
    if (!is_null($comments['Comment']) && $i < 4)
    {?>
        <div id= "comment-container" class ="container">
            <?php 
            $i = $i + 1;
            $user = User::find()->where('id = :uid', [':uid'=>$comments['User_Id']])->one();
            $user = $user['username'];
            $dt = new DateTime('@'.$comments['created_at']);
            $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
             ?>
          <div class=' panel panel-default'>
		<div class='panel-body'>
            <div id = "rating">
           <span class="small-text pull-right stars" alt="<?php echo $comments['FoodRating_Rating']; ?>"> <?php echo $comments['FoodRating_Rating'];?> </span>
               
            </div>  
            <div id = "ratedatetime">
                <?php echo $dt->format('d-m-Y H:i:s');?>
            </div>
                        <br>
                       By <?php echo $user;?>
                        <br>
                        <br>
                        <?php echo $comments['Comment'];?>
         </div>
			</div>
                       
                       
        </div>
   <?php }
    endforeach; ?>
    <td><?php echo "<center>".Html::a(Yii::t('food','View All Comments'), ['view-comments', 'id'=>$fooddata['Food_ID']], ['class'=>'btn btn-default']); ?></td>
</div>
</div>