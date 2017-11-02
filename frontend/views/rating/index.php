<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\RatingIndexAsset;

$this->title = "Rating";
RatingIndexAsset::register($this);
?>
<div class="container">
	<h1>Rating</h1>
	<h4>Complete all ratings to earn more points!</h4><br>

	<?php $form = ActiveForm::begin(['action' => ['rating/rating-data','id'=>$id],'method' => 'post']);?>

	 <div class="tab-content">
  <div id="home" class="tab-pane fade in active">
		<div class="outer-container1">
			<div class="menu-container1">
			<div class="item1">
			<div class="inner-item">
				<?= $form->field($servicerating, 'DeliverySpeed')->inline()->radioList($ratingLevel,
							[
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
      <button class="btn btn-primary1" onclick="proceed()">Proceed</button> 
			
			
			</div>
			<div id="comments" class="tab-pane fade">
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
                    <?= $form->field($foodrating,'['.$k.']Comment')->textInput()->label('Leave a Comment') ?>
						</div>
					</div>
				<?php endforeach ;?>

		</div>
		</div>
		<button class="btn btn-primary2">Submit</button>
	</div>
	
	<?php ActiveForm::end();?>
</div>