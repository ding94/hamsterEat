<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\assets\DeliverymanOrdersAsset;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;

$this->title = "Collect Orders";
DeliverymanOrdersAsset::register($this);
?>
<style>
.inner-row{
  display: flex;
  flex-wrap: wrap;
  padding-left:10%;
}

</style>


<div class="container" id="deliveryman-orders-container">
    <div class="deliveryman-orders-header">
        <div class="deliveryman-orders-header-title"><?= Html::encode($this->title) ?>
		</div>
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
                    	<li role="presentation" class=<?php echo $name=="Complete Orders" ? "active" :"" ?>>
                    		<a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                    	</li>
                	<?php endforeach ;?>
                   
                </ul>
            </div>
        </div>
		<div id="deliveryman-orders-content" class="col-sm-10">
		<?php 
			$i =0;
		 	foreach ($data as $cname => $company) :
		 ?>
		<div class="order-inner">
			<div style="background-color:#fffced;padding-top:1px; padding-bottom:1px;"><h2><center> <?= $cname; ?></center> </h2> 
			</div>
			<?php $form = ActiveForm::begin(['action'=>['deliveryorder/mutiple-complete']]);?>
			<div id="parent1" style="padding-top:5px;">
				<div class="blocka left1"><div class="inner-rows" style=" display:flex;">
				Total Collect(RM): <p style="color:red;"><?= $company['collectprice']; ?></p></div>
				</div>
				<div class="blocka center1">
				<?php echo Html::a('Show Location',$company['address'] ,['class'=>'raised-btn secondary-btn','target'=>'_blank']);?>
				</div>
				<div class="blocka right1">
				<?= Html::submitButton('Complete', ['class' => 'raised-btn main-btn']) ?>
				</div>
			</div>
				<table class="table table-hover" style="border:0px solid black;">
					<thead class='none'>
						<tr>
							<th><div class="inner-row"><?php echo Html::checkbox('null',false ,['class'=>'check-all']) ?>Delivery ID</th>
							<th>Collect Price</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($company['id'] as $did => $price) : $i++ ;?>
						<tr>
							<td data-th="Order ID"><div class="inner-row"><?php echo Html::checkbox('did[]',false ,['label'=>$did ,'value'=> $did]) ?></div></td>
							<td data-th="Price"><?= $price; ?></td>
						</tr>		
						<?php endforeach; ?>
					</tbody>
				</table>
			 <?php ActiveForm::end();?> 
		</div>
		<?php endforeach; ?>
		
		
    </div>
</div>
</div>
</div>



