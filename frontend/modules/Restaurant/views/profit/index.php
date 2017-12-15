<?php
use yii\helpers\Json;
use frontend\controllers\CartController;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use frontend\assets\CookingAsset;
use frontend\assets\RestaurantEarningsAsset;

$this->title = "Restaurant Profit";
CookingAsset::register($this);
RestaurantEarningsAsset::register($this);
?>

<div class="container">
	<div class="restaurant-earnings-header">
        <div class="restaurant-earnings-header-title"><?= Html::encode($this->title) ?></div>
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
	                 <ul id="restaurant-earnings-nav" class="nav nav-pills nav-stacked">
	                   <?php foreach($link as $url=>$name):?>
	                   		<li role="presentation" class=<?php echo $name=="Views Earnings" ? "active" :"" ?>>
	                   			<a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
	                   		</li>	
	                   <?php endforeach ;?>
	                </ul>
	            </div>
	           
	        </div>
	    </div>
	    <div id="restaurant-earnings-content" class = "col-sm-10">
			<?php $form = ActiveForm::begin(['method' => 'get']); ?>
			<label class="control-label">Select Date</label>
			<div class="row">
				<div class="col-md-9">
					<?php
				    	echo DatePicker::widget([
					        'name' => 'first',
					        'value' => $first,
					        'type' => DatePicker::TYPE_RANGE,
					        'name2' => 'last',
					        'value2' => $last,
					        'pluginOptions' => [
					            'autoclose'=>true,
					            'format' => 'yyyy-m-d'
					        ]
					    ]);
					?>
				</div>
				<div class="col-md-3">
					<?= Html::submitButton('Filter', ['class' => 'btn-block raised-btn main-btn']) ?>
				</div>
			</div>
			<?php ActiveForm::end(); ?> 
			<br>
			<?php foreach($data as $did => $delivery):?>
				
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Delivery ID</th>
							<th>Order ID</th>
							<th>Single Price</th>
							<th>Quantity</th>
							<th>Cost</th>
							<th>Mark Up 30%</th>
							<th>Selling Price</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$rowspan = count($delivery);
							$sumprice = 0;
							$sumfinal = 0;
						?>
						<?php foreach($delivery as $i=>$order):?>
						<tr>
							<?php
								$original = $order->original;
								$cost = $order->cost;
								$sellprice = $order->sellPrice;
								//$singleprice = $order->originalPrice * $order->quantity;
								//$singlefinal = $order->finalPrice * $order->quantity;
								$sumprice +=  $cost;
								$sumfinal += $sellprice;
							?>
							<?php if($i == 0): ?>
								<td rowspan=<?php echo $rowspan?>><?php echo $did?></td>
							<?php endif ;?>
							<td><?php echo $order->oid?></td>
							<td><?php echo $original?></td>
							<td><?php echo $order->quantity?></td>
							<td><?php echo $cost ?></td>
							<td><?php echo  CartController::actionDisplay2decimal($sellprice - $cost) ?></td>
							<td><?php echo $sellprice?></td>
						</tr>
						
						<?php endforeach ;?>
						<tr><td colspan ="4"></td>
							<td>RM <?php echo CartController::actionDisplay2decimal($sumprice)?></td>
							<td>RM <?php echo CartController::actionDisplay2decimal($sumfinal - $sumprice)?></td>
							<td>RM <?php echo CartController::actionDisplay2decimal($sumfinal)?></td>
						</tr>
					</tbody>
				</table>
			<?php endforeach ;?>
		</div>
	</div>			
</div>
