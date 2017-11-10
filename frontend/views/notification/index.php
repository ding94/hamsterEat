<?php

use yii\helpers\Html;
use frontend\assets\NotificationAsset;

$this->title = "Notification";

NotificationAsset::register($this);
?>

<div class="container">
	<div class="row">
		<h3>Your Notification</h3>
		<?php if(empty($notification)) : ?>
			<h4>You  have not receive any notifcaiton yet</h4>
		<?php else :?>
			<?php foreach($notification as $i=> $notic) :?>
				<div class="panel panel-primary col-md-9">
					<h4><?php echo $list[$i]['description']?></h4>
				</div>
				<?php foreach($notic as $data):?>
					<?php $ago = Yii::$app->formatter->asRelativeTime($data['created_at']);?>
					<div class="col-md-9 notic">
						<?php if($data['type'] == 2 || $data['type'] == 4):?>
							<?php echo Html::a($data['description'].'<span class="pull-right">'.$ago.'</span>',['/order/order-details','did'=>$data['rid']],['class'=> 'a-notic'])?>
						<?php elseif($data['type'] == 1) :?>
							<?php echo Html::a($data['description'].'<span class="pull-right">'.$ago.'</span>',["order/restaurant-orders",'rid' => $data['rid']],['class'=> 'a-notic'])?>
						<?php else :?>
							<?php echo Html::a($data['description'].'<span class="pull-right">'.$ago.'</span>',[$list[$i]['url']],['class'=> 'a-notic'])?>
						<?php endif;?>
					</div>
				<?php endforeach ;?>
			<?php endforeach ;?>
		<?php endif ;?>
	</div>
</div>

