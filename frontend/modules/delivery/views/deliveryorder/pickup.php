<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\assets\DeliverymanOrdersAsset;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;

$this->title = Yii::t('m-delivery',"Pick Up Orders");
DeliverymanOrdersAsset::register($this);
?>
<div class="container" id="deliveryman-orders-container">
    <div class="deliveryman-orders-header">
        <div class="deliveryman-orders-header-title"><?= Html::encode($this->title) ?><?= Html::encode($this->title) ?> <?= Html::a('Orders PDF',['/Delivery/deliveryorder/company-orders-pdf'],['class'=>'btn btn-primary'])?></div>
    </div>
    <div class="content">
        <div class="col-sm-2">
            <div class="dropdown-url">
                 <?php 
                    echo Select2::widget([
                        'name' => 'url-redirect',
                        'hideSearch' => true,
                        'data' => $link,
                        'options' => [
                            'placeholder' => Yii::t('common','Go To ...'),
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
                    	<li role="presentation" class=<?php echo $name=="Pick Up Orders" ? "active" :"" ?>>
                    		<a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-delivery',$name) ?></a>
                    	</li>
                	<?php endforeach ;?>
                   
                </ul>
            </div>
        </div>
        <div id="deliveryman-orders-content" class="col-sm-10">
        	<?php 
        		$companyCount = 0;
        	?>
            <?php if (empty($data)): ?>
                <h3><?= Yii::t('m-delivery','You have no orders to deliver at the moment.')?></h3>
            <?php else :?>
               	<?php foreach ($data as $rname => $restaurant) : ?>
				<?php  
					$address = array_shift($restaurant);
				?>
				<div style="border:1px solid black;">
					
					<div class="inner-row"><h4><b><?php echo $rname; ?></b></h4>
						<div style="padding-left:20px;">
							<?php echo Html::a(Yii::t('m-delivery','Show Location'),"$address" ,['class'=>'raised-btn secondary-btn','target'=>'_blank']);?>
						</div>
					</div>
				<?php foreach($restaurant as $sname=> $order): ?>
				<?php  
					$totalOrders = count($order);
					$companyCount ++;
				?>	
					<?php $form = ActiveForm::begin(['method'=>'post','action'=>['/Delivery/deliveryorder/mutiple-pick']])?>

					<div id="parent">
					    <div class="block left">
							<a data-toggle="collapse" data-target="#<?php echo $companyCount; ?>"><i class="fa fa-plus-circle fa-lg" aria-hidden="true" style="color:#ffda00;padding-right:10px;"></i></a><?php echo $sname; ?>
						</div>
					    <div  class="block center"><?= Yii::t('order','Quantity')?>: <?php echo $totalOrders; ?></div>
				    	<div class="block right">
					 		<?= Html::submitButton(Yii::t('order','Picked Up'), ['class' => 'raised-btn main-btn']) ?>
					 	</div>
						<div id=<?= $companyCount; ?>  class="collapse">
							Order ID: 
							<?php foreach ($order as $i => $id ): ?>
							<?php $allid = explode(",",$id);?>
							<?= Html::hiddenInput('order['.$i.'][oid]', $allid[0]); ?>
							<?= Html::hiddenInput('order['.$i.'][did]', $allid[1]); ?>
							<div style="display:inline;"><?php echo $allid[0]; ?> 
								<div style="display:inline;color:#ffda00;">&nbsp;|</div>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
					<?php ActiveForm::end(); ?>
				<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			<?php endif ;?>
        </div>
    </div>
</div>