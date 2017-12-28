<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use frontend\assets\NotificationAsset;
use kartik\widgets\Select2;

$this->title = $title;

NotificationAsset::register($this);
?>

<div id="userprofile" class="row">
	<div class="userprofile-header">
       <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
    	<div class="col-sm-2">
    		<div class="dropdown-url">
                 <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => 'Go To ...',
                            'multiple' => false,

                        ],
                        'pluginEvents' => [
                             "change" => 'function (e){
                                location.href =this.value;
                            }',
                        ]
                    ])
                ;?>
            </div>
            <div class="nav-url">
                <ul id="deliveryman-orders-nav" class="nav nav-pills nav-stacked">
                	<?php foreach($link as $url=>$name):?>
                    	<li role="presentation" class=<?php echo $name== $title ? "active" :"" ?>>
                    		<a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                    	</li>
                	<?php endforeach ;?>
                   
                </ul>
            </div>
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
						<?php echo Html::a($notic['description'],['/order/order-details','did'=>$notic['rid']],['class'=> 'a-notic'])?>
						<span class="pull-right">From <?php echo $ago?></span>	
					<?php elseif($notic['type'] == 1) :?>
						<?php echo Html::a($notic['description'],["/order/restaurant-orders",'rid' => $notic['rid']],['class'=> 'a-notic'])?>
						
						<span class="pull-right">From <?php echo $ago?></span>	
					
					<?php else :?>
						<?php echo Html::a($notic['description'],["/order/deliveryman-orders"],['class'=> 'a-notic'])?>
						
						<span class="pull-right">From <?php echo $ago?></span>	
					
					<?php endif;?>
				</div>
			<?php endforeach ;?>
        <?php echo LinkPager::widget([
            'pagination' => $pages,
        ]);?>
		<?php endif ;?>
    		
    </div>

</div>