<?php

use yii\helpers\Html;

$this->title = "Notification";
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
						<a class="a-notic" href="#"><?php echo $data['description']?><span class="pull-right"><?php echo $ago ?></span></a>
					</div>
				<?php endforeach ;?>
			<?php endforeach ;?>
		<?php endif ;?>
	</div>
</div>