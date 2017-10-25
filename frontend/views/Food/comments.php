<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Orderitemselection;
use frontend\controllers\CartController;
use kartik\widgets\TouchSpin;
use common\models\User;
$this->title = "Comments";

foreach ($comments as $comments) :
    if (!is_null($comments['Comment']))
    {?>
    <style>
    .panel.panel-default{
        width:598px;
    }
    </style>

        <div class ="container">
            <?php 
            $user = User::find()->where('id = :uid', [':uid'=>$comments['User_Id']])->one();
            $user = $user['username'];
            $dt = new DateTime('@'.$comments['created_at']);
            $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
             ?>
          <div class='panel panel-default'>
		<div class='panel-body'>
             <?php echo $comments['FoodRating_Rating'];?>  <?php echo $dt->format('d-m-Y H:i:s');?> 
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