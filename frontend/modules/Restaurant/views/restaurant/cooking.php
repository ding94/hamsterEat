<?php
use yii\helpers\Json;
use yii\helpers\Html;
use frontend\assets\CookingAsset;

$this->title = "Cooking List";
CookingAsset::register($this);

$quantity = 0;
?>

<div class="container">
	<?php echo Html::button(Yii::t('m-restaurant','View Nicknames'), ['class'=>'raised-btn btn-default fa fa-exchange swap-button pull-right switch name'])?>
<div class="panel">
	<div class="panel-heading">
		<ul class="nav nav-tabs">
			<?php foreach($companyData as $name=> $company):?>
			<li >
				<?php 
					$symbol = array("!","@",'#','$','%','&','*');
					$recname = str_replace($symbol,"-", $name); 
				?>
				<a href="#<?php echo $recname?>" data-toggle="tab"><?php echo $name?></a>
			</li>
			<?php endforeach ;?>
			<?php foreach($singleData as $name => $single):?>
			<li>
				<a href="#<?php echo $name?>" data-toggle="tab">Delivery ID: <?php echo $name?></a>
			</li>
			<?php endforeach ;?>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<?php foreach($companyData as $name=> $company):?>
			<?php 
				$quantity = 0;
				$symbol = array("!","@",'#','$','%','&','*');
				$recname = str_replace($symbol,"-", $name); 
			?>
			<div class="tab-pane cooking-table" id = <?php echo $recname?>>
				<table class="table table-bordered">
				<thead>
					<tr>
						<th><?= Yii::t('order','Food Name')?></th>
						<th><?= Yii::t('cart','Food Selection')?></th>
						<th><?= Yii::t('order','Quantity')?></th>
						<th><?= Yii::t('m-restaurant','Order Id')?></th>
						<th><?= Yii::t('m-restaurant','Order Quantity')?></th>
						<th><?= Yii::t('common','Remarks')?></th> 
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach($company as $name=> $food):
						$rowspan = array_shift($food);
						$nickname = array_shift($food);
						foreach($food as $i=>$single):
						$arrayKey = array_keys($single['orderid']);
						$orderrow = count($arrayKey);
						foreach($arrayKey as $index => $orderid):
					?>
					<tr>
					<?php 
						if( $arrayKey[0] == $orderid):
						if($i == 0 ) :
					?>
						<td class="thead" data-th="Food Name" rowspan=<?php echo $rowspan?>><?php echo $name?></td>
						<td class="thead none" data-th="Nick Name" rowspan=<?php echo $rowspan?>><?php echo $nickname?></td>			
						<?php endif ;?>
						<td class="selection-thead" data-th="Food Selection" rowspan=<?php echo $orderrow?>>
						<?php 
							foreach($single['selection'] as $id => $selection) :
								echo $selection['name'].",";
							endforeach 
						;?>
						</td>
						<td class="selection-thead none" data-th="Food Selection" rowspan=<?php echo $orderrow?>>
						<?php 
							foreach($single['selection'] as $id => $selection) :
							    echo $selection['nick'].",";
							endforeach 
						;?>
						</td>
						<td data-th="Quantity" rowspan=<?php echo $orderrow?>>
							<?php echo $single['quantity'];
									$quantity += $single['quantity'];
							?>
						</td>
						<?php endif ;?>
						<td data-th="Order ID"><?php echo $orderid;?></td>
						<td data-th="Order Quantity"><?php echo $single['orderid'][$orderid]['single_quantity'] ?></td>
						<td data-th="Remark"><?php echo $single['orderid'][$orderid]['remark'] ?></td>
					</tr>
					<?php 
						endforeach; 
					 	endforeach;
					 	endforeach;
					?>
					<tr>
						<td colspan="2"  class="mobile-hide">Total Quantity</td>
						<td class="mobile-hide"><?php echo $quantity?></td>
					</tr>
				</tbody>
				</table>				
			</div>
			<?php 
				endforeach ;
			 	foreach($singleData as $name => $single):
			?>
			<div class="tab-pane fade" id = <?php echo $name?>>
				<table class="table table-hover">
				<thead>
					<tr>
						<th>Food Name</th>
						<th>Food Selection</th>
						<th>Quantity</th>
						<th>Order Id</th>
						<th>Remark</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="thead" data-th="Food Name"><?php echo $single['foodname']; ?></td>
						<td class="selection-thead" data-th="Food Selection">
							<?php 
							foreach($single['selection'] as $id => $selection) :
								 echo $selection;
							endforeach;
							?>
						</td>
						<td data-th="Quantity"><?php echo $single['quantity'];?></td>
						<td data-th="Order ID"><?php echo $single['orderid']?></td>
						<td data-th="Remark"><?php echo $remark = empty($single['remark']) ? "" :$single['remark'] ?></td>
					</tr>
							
				</tbody>
				</table>		
			</div>
			<?php endforeach ;?>
		</div>
	</div>
</div>
</div>
