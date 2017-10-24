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
        <div class ="container">
            <?php 
            $user = User::find()->where('id = :uid', [':uid'=>$comments['User_Id']])->one();
            $user = $user['username'];
            $dt = new DateTime('@'.$comments['created_at']);
            $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
            echo $user.' '.$comments['Comment'].' '.$comments['FoodRating_Rating'].' '.$dt->format('d-m-Y H:i:s'); ?>
        </div>
   <?php }
    endforeach; ?>