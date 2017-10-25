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
$this->title = "Food Details";

?>
<style>

.modal-header{
  padding:0px;
  
}
.modal-header .close {
  margin: 0;
  position: absolute;
  top: -10px;
  right: 10px;
  width: 23px;
  height: 23px;
  border-radius: 23px;
  background-color: grey;
  color: white;
 
  opacity: 1;
  z-index: 10;
}
#a2cart {
  background-color:#fff;
}

.modal-content{
  width:598px;
  background-color:#fff;
}
.modal-lg{
  padding-left: 158px;
}
.value-button {
  border: 1px solid #ddd;
  margin: 0px;
  width: 40px;
  background: #eee;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.value-button:hover {
  cursor: pointer;
}

#input-wrap {
  margin: 0px;
  padding: 0px;
}

#number {
  text-align: center;
  border: none;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  margin: 0px;
  width: 40px;
  height: 40px;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

#fooddetails td{
  padding: 10px 0em 10px 0em;
}

.bordertop{
  border-top: 1px solid #D3D3D3;
}
#fooddetails .foodname{
line-height: initial;
  font-size: 2.0em;
}
#fooddetails .foodprice{
line-height: initial;
  font-size: 1.6em;
}
#fooddetails .cart{
 
  
}

#fooddetails .description{
      color: rgb(117, 117, 117);
      font-size: 14px;
      line-height: 22px;
}
#fooddetails .selection{
font-size: 16px;

}
.food-detail-label{
  width: 100%;

}

#rating {
    float:left;
}

#ratedatetime {
    float:right;
}
button.btn.btn-primary.bootstrap-touchspin-down{
width:40px;
height:40px;
}
button.btn.btn-primary.bootstrap-touchspin-up{
width:40px;
height:40px;
}
/*-----Comment------*/
.panel-default{
        width:551px;
 }
#comments.tab-pane.fade{
   background-color:#fff;
 }
</style>
  <ul style = "margin-left:37%;" class="nav nav-pills">
    <li class="active"><a data-toggle="pill" href="#home">Home</a></li>
    <li><a data-toggle="pill" href="#comments">Comments</a></li>
  </ul>
  <body>
  <div class="tab-content">
  <div id="home" class="tab-pane fade in active">
<div class="row" style="padding-bottom: 0px">
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
                 <span style="display: block;overflow-wrap: break-word; word-wrap: break-word; width:148px;"><?php echo $fooddata->Description;?></span>
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
        
           
      			<div class="cart">
             
              <?= $form->field($orderitem, 'OrderItem_Quantity',['options'=>['style'=>'width:22%;']])->widget(TouchSpin::classname(), [
                  'options' => [
                      'id'=>'orderitem-orderitem_quantity'.$fooddata->Food_ID,
                      'style'=>'height:40px;'
                  ],
                  'pluginOptions' => [
                      'min' => 1,
                      
                      'initval' => 1,
                      'buttonup_class' => 'btn btn-primary', 
                      'buttondown_class' => 'btn btn-primary', 
                      'buttonup_txt' => '<i class="fa fa-plus"></i>', 
                      'buttondown_txt' => '<i class="fa fa-minus"></i>'
                  ],
              ])->label(false); ?>   <?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary pull-right', 'name' => 'addtocart','style'=>'margin-top:-44px;width:48%; height:48px; font-size: 18px;']) ?>

     
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
<div id="comments" class="tab-pane fade">
<?php
$i = 1;
foreach ($comments as $comments) :
    if (!is_null($comments['Comment']) && $i < 4)
    {?>
        <div class ="container">
            <?php 
            $i = $i + 1;
            $user = User::find()->where('id = :uid', [':uid'=>$comments['User_Id']])->one();
            $user = $user['username'];
            $dt = new DateTime('@'.$comments['created_at']);
            $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
             ?>
          <div class='panel panel-default'>
		<div class='panel-body'>
            <div id = "rating">
                <?php echo $comments['FoodRating_Rating'];?> 
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
</body>
