<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\food\Foodselection;
use yii\helpers\ArrayHelper;
use common\models\Order\Orderitemselection;
use frontend\controllers\CartController;
use kartik\widgets\TouchSpin;
use common\models\User;
use frontend\assets\StarsAsset;
use frontend\assets\CommentsAsset;
$this->title = Yii::t('food','Comments');

StarsAsset::register($this);
CommentsAsset::register($this);
?>
<div class ="container">
<h2 id='title'><?= Yii::t('food','All comments for') ?> <?= $foodname; ?></h2>
<?php foreach ($comments as $comments) :
    if (!is_null($comments['Comment']))
    {?>
        <div class ="container">
            <?php 
            
            $user = User::find()->where('id = :uid', [':uid'=>$comments['User_Id']])->one();
            $user = $user['username'];
            $dt = new DateTime('@'.$comments['created_at']);
            $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
             ?>
          <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 panel panel-default'>
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
    </div>