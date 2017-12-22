<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use frontend\assets\NotificationAsset;

$this->title = "Notification";

NotificationAsset::register($this);
?>

<div id="userprofile" class="row">
	<div class="userprofile-header">
       <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
    	<div class="col-sm-2">
    		<ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">All Notification</a></li>
            </ul>
    	</div>
    </div>
    <div class="col-sm-10 notifcation-right">
		<?php if(empty($notification)) : ?>
			<h4>You  have not receive any notifcaiton yet</h4>
		<?php else :?>
			<?php foreach($notification as $i=> $notic) :?>
				
				<?php $ago = Yii::$app->formatter->asRelativeTime($notic['created_at']);?>
				<div class="col-md-12 notic">
					<?php if($notic['type'] == 2 || $notic['type'] == 4):?>
					<div>
						<?php echo Html::a($notic['description'],['/order/order-details','did'=>$notic['rid']],['class'=> 'a-notic'])?>
					
						<span class="pull-right">From <?php echo $ago?></span>	
					</div>
					<?php elseif($notic['type'] == 1) :?>
					<div>
						<?php echo Html::a($notic['description'],["/order/restaurant-orders",'rid' => $notic['rid']],['class'=> 'a-notic'])?>
						
						<span class="pull-right">From <?php echo $ago?></span>	
					</div>
					<?php else :?>
					<div>

						<?php echo Html::a($notic['description'],["/order/deliveryman-orders"],['class'=> 'a-notic'])?>
						
						<span class="pull-right">From <?php echo $ago?></span>	
					</div>
					<?php endif;?>
				</div>
			<?php endforeach ;?>

		<?php endif ;?>
    	<?php echo LinkPager::widget([
			'pagination' => $pages,
		]);?>	
    </div>

</div>