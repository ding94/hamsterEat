<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;
use frontend\controllers\CartController;
use kartik\widgets\TouchSpin;
use kartik\widgets\DatePicker;
use common\models\User;
use frontend\assets\StarsAsset;
use frontend\assets\FoodDetailsAsset;
$this->title = "Food Details";

StarsAsset::register($this);
FoodDetailsAsset::register($this);
?>
<div id="nav">
  <ul class="nav nav-pills food-details-tab">
    <li class="active"><a data-toggle="pill" href="#home">Food Details</a></li>
    <li ><a data-toggle="pill" href="#comments">Comments</a></li>
  </ul>
</div>
  <div class="tab-content">
  <div id="home" class="tab-pane fade in active"><a name="home"></a>
  <div class="row">
  	<div class="tab-content col-md-12" id="fooddetails">

      <?php if($fooddata->foodPackage == 1) :?>
     
        <?php $form = ActiveForm::begin(['action' => ['UserPackage/package/subscribepackage'],'id' => 'a2cart' ,'method' => 'get']); ?>
      <?php else :?>
        <?php $form = ActiveForm::begin(['id' => 'a2cart']); ?>
      <?php endif ;?>
  		<!--<table class="table-user-information" style="width:60%; margin:auto;">-->
     
                   
                    <!--<?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata->PicPath, ['class' => 'img-rounded img-responsive','style'=>'height:300px; width:598px; margin:auto;']) ?>-->
              
        
             <br>
              <div class="foodname">
                    <!--<td>Food Name:</td>-->
                     <?php echo $fooddata->Name;?>
            </div>
              
          <div class="foodprice">
                    <!--<td>Food Price (RM):</td>-->
                 RM <?php echo CartController::actionRoundoff1decimal($fooddata->Price);?>
                     </div>
            
        <br>
              <div class="description">
                   <!--<td>Food Description:</td>-->
                   <span><?php echo $fooddata->Description;?></span>
                   </div>
              <br>
                <div class="selection">

              <?php  
                $ftids = "";
                foreach($foodtype as $k=> $foodtype) : 
                  $selection = Foodselection::find()->where('Type_ID = :ftid',[':ftid' => $foodtype['ID']])->orderBy(['Price' => SORT_ASC])->all();
                  $data = ArrayHelper::map($selection,'ID','typeprice');
                  if ($foodtype['Min'] == 1 && $foodtype ['Max'] < 2 ) {
                    ?>
                   
                      
                        <?php echo $foodtype['TypeName']; ?>

                        <span>*Please Select only 1 item.</span>
                      
                      
                        <?= $form->field($orderItemSelection,'FoodType_ID['.$foodtype['ID'].']')->radioList($data,[
                                  'item' => function($index, $label, $name, $checked, $value) {

                                      $return = '<div class="radio">';
                                      $return .= '<label class="food-detail-label">';
                                      $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" >';
                                      $return .= $label;
                                      $return .= '</label>';
                                      $return .= '</div>';

                                      return $return;
                                  }
                              ])->label(false); ?>
                      
                    
                <?php } else if ($foodtype['Min'] == 0){ ?>
                    
                      
                        <?php echo $foodtype['TypeName']; ?>
                       
                        <span>
                          *Select at most <?php echo $foodtype ['Max']; ?> items.
                        </span>
                      
                     
                        <?= $form->field($orderItemSelection,'FoodType_ID['.$foodtype['ID'].']')->checkboxlist($data,[
                                  'item' => function($index, $label, $name, $checked, $value) {

                                      $return = '<div class="checkbox">';
                                      $return .= '<label class="food-detail-label">';
                                      $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" >';
                                      $return .= $label;
                                      $return .= '</label>';
                                      $return .= '</div>';

                                      return $return;
                                  }
                              ])->label(false);?>
                    
                  
                <?php } else { ?>
                   
                     
                        <?php echo $foodtype['TypeName']; ?>
                   
                        <span>
                          *Select at least <?php echo $foodtype['Min']; ?> item and at most <?php echo $foodtype ['Max']; ?> items.
                        </span>
                     
                    
                        <?= $form->field($orderItemSelection,'FoodType_ID['.$foodtype['ID'].']')->checkboxlist($data,[
                                  'item' => function($index, $label, $name, $checked, $value) {

                                      $return = '<div class="checkbox">';
                                      $return .= '<label class="food-detail-label">';
                                      $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" >';
                                      $return .= $label;
                                      $return .= '</label>';
                                      $return .= '</div>';

                                      return $return;
                                  }
                              ])->label(false);?>
                   
                  
                <?php } endforeach; ?>
                 </div>
             
                 <?= $form->field($orderitem, 'OrderItem_Remark')->label('Remarks'); ?>
          
             
        			<div>
               
                <?= $form->field($orderitem, 'OrderItem_Quantity',['options'=>['style'=>'width:25%;']])->widget(TouchSpin::classname(), [
                    'options' => [
                        'id'=>'orderitem-orderitem_quantity'.$fooddata->Food_ID,
                        'style'=>'height:40px;'
                    ],
                    'pluginOptions' => [
                        'min' => 1,
                        'max'=>100,
                        'initval' => 1,
                        'buttonup_class' => 'btn btn-primary', 
                        'buttondown_class' => 'btn btn-primary', 
                        'buttonup_txt' => '<i class="fa fa-plus"></i>', 
                        'buttondown_txt' => '<i class="fa fa-minus"></i>'
                    ],
                ])->label(false); ?>   <?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary pull-right', 'name' => 'addtocart','style'=>'margin-top:-51px;width:48%; height:48px; font-size: 18px;']) ?>

       
             </div>

              <?php if($fooddata->foodPackage == 0):?>
  			      <!--<tr><td colspan="2"><?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary pull-right', 'name' => 'addtocart', 'style'=>'margin-bottom:25px;']) ?>-->
             
              <?php else :?>
           
             
                  <label class="control-label">Select Date to delivery</label>
                  <?php
                    echo DatePicker::widget([
                      'name' => 'dateTime',
                      'type' => DatePicker::TYPE_COMPONENT_APPEND,
                      'pluginOptions' => [
                          'format' => 'yyyy/mm/dd/',
                          'multidate' => true,
                          'multidateSeparator' => ',',
                          'startDate' => date('Y/m/d',strtotime("+2 day")),
                      ]
                    ]);
                  ?>

              <?= $form->field($fooddata,'Food_ID')->hiddenInput() ?>
            <?= Html::submitButton('Subscribe Food Package', ['class' => 'btn btn-primary pull-right', 'name' => 'addtocart', 'style'=>'margin-bottom:25px;']) ?>
             
        <?php endif ;?>
  		        
              <!--</table>-->
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
    <td><?php echo "<center>".Html::a('View All Comments', ['view-comments', 'id'=>$fooddata['Food_ID']], ['class'=>'btn btn-default']); ?></td>
</div>

</div>
