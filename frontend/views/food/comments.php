<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;
use frontend\controllers\CartController;
use kartik\widgets\TouchSpin;
$this->title = "Comments";

foreach ($comments as $comments) :
    if (!is_null($comments['Comment']))
    {?>
        <div class ="container">
            <?php echo $comments['Comment'].' '.$comments['FoodRating_Rating'].' '.$comments['created_at']; ?>
        </div>
   <?php }
    endforeach; ?>