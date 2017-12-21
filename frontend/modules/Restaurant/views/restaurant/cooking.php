<?php
use yii\helpers\Json;
use frontend\assets\CookingAsset;

$this->title = "Cooking List";
CookingAsset::register($this);
?>

<div class="container">
	<div class="panel">
		<div class="panel-heading">
			<ul class="nav nav-tabs">
				<?php foreach($companyData as $name=> $company):?>
					<li >
						<a href="#<?php echo $name?>" data-toggle="tab"><?php echo $name?></a>
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
				<div class="tab-pane cooking-table" id = <?php echo $name?>>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Food Name</th>
								<th>Food Selection</th>
								<th>Quantity</th>
								<th>Order Id</th>
								<th>Order Quantity</th>
								<th>Remark</th> 
							</tr>
						</thead>
						<tbody>
							<?php foreach($company as $name=> $food):?>
								<?php $rowspan = array_shift($food);?>
								<?php foreach($food as $i=>$single):?>
								<?php  
									$arrayKey = array_keys($single['orderid']);
						            $orderrow = count($arrayKey);
						        ?>
							        <?php foreach($arrayKey as $index => $orderid):?>
									<tr>
										<?php if( $arrayKey[0] == $orderid):?>
											<?php if($i == 0 ) :?>
												<td class="thead" data-th="Food Name" rowspan=<?php echo $rowspan?>><?php echo $name?></td>
											<?php endif ;?>
							                	<td class="selection-thead" data-th="Food Selection" rowspan=<?php echo $orderrow?>>
							                      <?php foreach($single['selection'] as $id => $selection) :?>
							                        <?php echo $selection?>
							                      <?php endforeach ;?>
							                    </td>
							                    <td data-th="Quantity" rowspan=<?php echo $orderrow?>><?php echo $single['quantity']?></td>
						                <?php endif ;?>
						                <td data-th="Order ID"><?php echo $orderid ?></td>
										<td data-th="Order Quantity"><?php echo $single['orderid'][$orderid]['single_quantity'] ?></td>
										<td data-th="Remark"><?php echo $single['orderid'][$orderid]['remark'] ?></td>
									</tr>
									<?php endforeach ;?>
								<?php endforeach;?>
							<?php endforeach ;?>
						</tbody>
					</table>				
				</div>
				<?php endforeach ;?>
				<?php foreach($singleData as $name => $single):?>
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
									<?php foreach($single['selection'] as $id => $selection) :?>
										<?php echo $selection?>

									<?php endforeach ;?>
								</td>
								<td data-th="Quantity"><?php echo $single['quantity'] ?></td>
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
