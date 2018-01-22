<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

	$this->title = Yii::t('rating','Rating');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
h1,
h4{
	text-align:center;
}

.outer-container1{
  display:flex;
  align-items: center;
  justify-content:center;
  margin-top:-20px;
}

.menu-container1{
  display: grid;
  width:1000px;
  grid-template-columns: 1fr;
  align-items: center;
  justify-content:center;
}
.item1{
  font-size: 12px;
  color: black;
  background-color: white;

}
.btn-primary1 {
	margin-left: 500px;
    margin-top: 20px;
	color: black;
    background-color: #fed136;
    border-color: #fed136;
}
.rating{
	font-size: 30px;
	text-align:center;
	margin-left: 5px;
	align:center;
}

.outer-container2{
  display:flex;
  align-items: center;
  justify-content:center;
}

.menu-container2{
  display: grid;
  width:800px;
  grid-template-columns: 1fr;
  align-items: center;
  justify-content:center;
}

.item2{
  font-size: 12px;
  color: black;
  background-color: white;
  min-width: 600px;
  min-height: 150px;
  border-bottom:1px solid #FFDA00;
}
.inner-item{
  margin:15px 0px 10px 50px;
  float:left;
  
}
.img{

  float:left;
}
.img img{
  margin-top:15px;
  margin-left: 20px;
  width:150px;
  height:150px;
}
.btn-primary2 {
	margin-left: 500px;
    margin-top: 20px;
	color: black;
    background-color: #fed136;
    border-color: #fed136;
}
</style>
<div class="container">
  <h1><?= Yii::t('rating','Rating') ?></h1>
  <h4><?= Yii::t('rating','Complete all ratings to earn more points!') ?></h4><br>
  <div id="nav">
    <ul style = "margin-left:37%;" class="nav nav-pills">
      <li class="active"><a data-toggle="pill" href="#home"><?= Yii::t('rating','Service Rating') ?></a></li>
      <li><a data-toggle="pill" href="#comments"><?= Yii::t('rating','Food Rating') ?></a></li>
    </ul>
  </div>

  <?php $form = ActiveForm::begin(['action' => ['rating/rating-data','id'=>$id],'method' => 'post']);?>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active"><a name="home"></a>
      <div class="tab-content col-md-6 col-lg-offset-3" id="fooddetails"><br>
        <div class="outer-container1">
          <div class="menu-container1">
            <div class="item1">
              <div class="inner-item">
                <?= $form->field($servicerating, 'DeliverySpeed')->inline()->radioList($ratingLevel,[
                  'item' => function($index, $label, $name, $checked, $value) {
                    $return = '<label class="radio-inline">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '">';
                    $return .= '<i></i>';
                    $return .= '<span class="rating">' . $label . '</span>';
                    $return .= '</label>';

                    return $return;
                  }
                ]) ?>

                <?= $form->field($servicerating, 'UserExperience')->inline()->radioList($ratingLevel,[
                  'item' => function($index, $label, $name, $checked, $value) {
                    $return = '<label class="radio-inline">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '">';
                    $return .= '<i></i>';
                    $return .= '<span class="rating">' . $label . '</span>';
                    $return .= '</label>';

                    return $return;
                  }
                ])?>

                <?= $form->field($servicerating, 'Service')->inline()->radioList($ratingLevel,[
                  'item' => function($index, $label, $name, $checked, $value) {
                    $return = '<label class="radio-inline">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '">';
                    $return .= '<i></i>';
                    $return .= '<span class="rating">' . $label . '</span>';
                    $return .= '</label>';

                    return $return;
                  }
                ]) ?>

                <?= $form->field($servicerating,'Comment')->textInput() ?>
              </div>
            </div>
          </div>
        </div>
      </div>
  			
      <button class="btn btn-primary1"><?= Yii::t('rating','Proceed') ?></button>
    </div>
    <div id="comments" class="tab-pane fade"><a name=""></a>
      <div class="outer-container2">
        <div class="menu-container2">
          <?php foreach($orderitem as $k => $data):?>
            <div class="item2">
              <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$data['food']['PicPath']) ?></div>
              <div class="inner-item">
                <?= $form->field($foodrating , '['.$k.']FoodRating_Rating')->inline()->radioList($ratingLevel,[
                  'item' => function($index, $label, $name, $checked, $value) {
                    $return = '<label class="radio-inline">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '">';
                    $return .= '<i></i>';
                    $return .= '<span class="rating">' . $label . '</span>';
                    $return .= '</label>';

                    return $return;
                  }
                ])->label($data['food']['Name'])	?>

                <?= $form->field($foodrating,'['.$k.']Food_ID')->hiddenInput(['value' => $data['Food_ID']])->label(false) ?>
                <?= $form->field($foodrating,'['.$k.']Comment')->textInput()->label(Yii::t('rating','Leave a Comment')) ?>
              </div>
            </div>
          <?php endforeach ;?>
        </div>
      </div>
      <button class="btn btn-primary2"><?= Yii::t('rating','Submit') ?></button>
    </div>
    <?php ActiveForm::end();?>
  </div>
</div> <!-- maybe left 1 div, try add 19/1/12018 -->