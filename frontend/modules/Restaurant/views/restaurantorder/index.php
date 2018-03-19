<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\widgets\ActiveForm;
use common\models\Order\Orderitem;
use common\models\Order\Orderitemselection;
use common\models\food\Foodselection;
use common\models\food\Foodselectiontype;
use frontend\assets\RestaurantOrdersAsset;

$this->title = $title;
RestaurantOrdersAsset::register($this);
?>

<div id="restaurant-orders-container" class = "container">
	<div class="restaurant-orders-header">
        <div class="restaurant-orders-header-title"><?= Html::encode($this->title) ?></div>
    </div>
    <a href="#top" class="scrollToTop"></a>
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
                <ul id="restaurant-orders-nav" class="nav nav-pills nav-stacked">
                 	 <li><?php echo Html::a("<i class='fa fa-chevron-left'></i> ".Yii::t('common','Back'),['history', 'rid'=>$rid])?></li>
                    <li><?php echo Html::a(Yii::t('common',"All"),['index', 'rid'=>$rid])?></li>
                    <?php 
                    	foreach($count as $i=> $single):
                    	 	$total = $single == 0 ? "" : $single;
                    ?>
                      <li><?php echo Html::a(Yii::t('order',$i).'<span class="badge">'.$total.'</span>',['/Restaurant/restaurantorder/index','status'=>$allstatus[$i],'rid'=>$rid])?></li>
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="restaurant-orders-content" class="col-sm-10">
            <?php 
            	echo Html::a(Yii::t('m-restaurant','Cooking Detail'),['/Restaurant/restaurant/cooking-detail','rid'=>$rid],['class'=>'btn btn-default','style'=>'margin-bottom:20px;','target'=>'_blank']);
             	if (empty($item)) :
            ?>		
            	<h2><?= Html::encode(Yii::t('m-restaurant','There are no orders currently').'...');?></h2>
        	<?php else :?>
        		 <div class = "switchbutton"> 
	                <?php
	                    if ($mode == 1)
	                    {
	                        echo Html::a(Yii::t('m-restaurant','View Nicknames'), ['index', 'rid'=>$rid, 'status'=>$status, 'mode'=>2], ['class'=>'raised-btn btn-default fa fa-exchange swap-button']);
	                    }
	                    else
	                    {
	                        echo Html::a(Yii::t('m-restaurant','View Food Names'), ['index', 'rid'=>$rid, 'status'=>$status, 'mode'=>1], ['class'=>'raised-btn btn-default fa fa-exchange swap-button']);
	                    } 
	                ?>
	            </div> 
        		<?php foreach($item as $name => $delivery): ?>
        			<div class="outer-div" style="border:1px solid black;">
        				<div class="row">
        					<div class="col-md-4">
        						<?php if($status == 2 || $status == 3):
                                    $form = ActiveForm::begin(['method'=>'post','action'=>['/Restaurant/restaurantorder/mutiple-order','status'=>$status,'rid'=>$rid]])?>
                                	<h2><center><?php echo Html::checkbox('null',false ,['class'=>'check-all','id'=>'order']) ?></center></h2>
                            	<?php endif ;?>
        					</div>
        					<div class="col-md-4">
	                            <h2><center><?= $name; ?></center> </h2> 
	                        </div>
	                        <div clas="col-md-4">
	                        	<?php 
                                if($status == 2 || $status == 3): 
	                                $statusname = $status == 2 ? 'Preparing' : 'Ready for Pickup';
	                            ?>
                                	<h2><center><?= Html::submitButton(Yii::t('order',$statusname), ['class' => 'raised-btn main-btn']) ?></center></h2>
                                <?php endif ;?>
	                        </div>
        				</div>
	        			<table class="table table-hover" style="border:0px solid black;">
	        				<?php foreach($delivery as $id => $deliveryitem) :?>
	        					<thead>
		                            <tr>
		                                <th colspan = '6' data-th="Delivery_ID" style="background-color:#fffced;"><center><?= Yii::t('common','Delivery ID')?>: <?= $id; ?> </th>
		                            </tr>
		                        </thead>
		                        <thead class='none'>
		                            <tr>
		                                <th><?= Yii::t('order','Order ID')?></th>
		                                <th><?php echo $mode == 1 ? Yii::t('order','Food Name') : Yii::t('food','Nickname') ?></th>
		                                <th><?= Yii::t('order','Selections')?> </th>
		                                <th><?= Yii::t('order','Quantity')?> </th>
		                            <!--    <th> Remarks </th>-->
		                                <th><?= Yii::t('m-restaurant','Update Status')?> </th>
		                            </tr>
		                        </thead>
		                        <tbody>
		                        	<?php foreach($deliveryitem as $key => $data): ?>
		                        	<tr>
		                        		<td data-th="Order ID">
		                        		<?php 
			                                if($status == 2 || $data['OrderItem_Status'] == 3):
			                                    echo Html::checkbox('oid[]',false ,['label'=>$data['Order_ID'] ,'value'=> $data['Order_ID']]);
			                                else :
			                                    echo $data['Order_ID']; 
			                                endif ;
			                            ?>
		                        		</td>
		                        		<td data-th="Food Name">
		                        			<?php echo $mode == 1 ? $data['food']['originName'] : $data['food']['Nickname'] ?>
		                        		</td>
		                        		<td>
		                        			<?php 
		                        				$selections = Orderitemselection::find()->where('Order_ID = :oid',[':oid'=>$data['Order_ID']])->all(); 
		                        				foreach ($selections as $selections) :
				                                    $selectionname = Foodselection::find()->where('ID =:sid',[':sid'=>$selections['Selection_ID']])->one();

				                                    $selectiontype = Foodselectiontype::find()->where('ID = :fid', [':fid'=>$selections['FoodType_ID']])->one();
				                                    if (!is_null($selectionname['ID']))
				                                    { 
				                                        $name = $mode == 1 ? $selectionname['originName'] : $selectionname['Nickname'];
				                                        echo $selectiontype['originName'].': &nbsp;'.$name;
				                                        echo "<br>";
				                                    }
			                                    endforeach; 
			                                    ?>
		                        		</td>
		                        		<td data-th="Quantity">
		                        			<?= $data['OrderItem_Quantity']; ?>
		                        		</td>
		                        		<td data-th="Update Status">
		                        			<?php if ($data['OrderItem_Status'] == 2): 
		                        				echo Html::a(Yii::t('order','Preparing'), ['preparing', 'oid'=>$data['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn']);
		                        			elseif($data['OrderItem_Status'] == 3):
		                        				echo Html::a(Yii::t('order','Ready for Pickup'), ['readyforpickup', 'oid'=>$data['Order_ID'], 'rid'=>$rid], ['class'=>'raised-btn main-btn']); 
		                        			elseif($data['OrderItem_Status'] == 4):?>
		                        				<span class='label label-warning'><?= Yii::t('order','Waiting for Pick Up')?> </span>
		                        					<?php else :?>
		                        						<span></span>
		                        			<?php endif; ?>
		                        		</td>
		                        	</tr>
		                        	<?php endforeach; ?>
		                        </tbody>
	        				<?php endforeach; ?>
	        			</table>
	        			<?php 
	        				if($status == 2 || $status == 3):
                        	 	ActiveForm::end();
                    	 	endif ;
                    	?>
        			</div>
        	<?php
        			endforeach;
        		endif 
        	;?>
        </div>
    </div>	
</div>