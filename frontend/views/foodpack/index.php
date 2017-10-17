<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
?>
<h2><center>Menu</h2>
<?php foreach($food as $data):
  	Modal::begin([
            'header' => '<h2 class="modal-title">Food Details</h2>',
            'id'     => 'modal'.$data['Food_ID'],
            'size'   => 'modal-lg',
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    echo "<div id='modelContent".$data['Food_ID']."'></div>";
    
    Modal::end() ?>
<?php endforeach; ?>

<div class="outer-container">
	<div class ="menu-container">
		<?php foreach($food as $data):?>
			 <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$data['Food_ID'],'rid'=>$data['restaurant']['Restaurant_ID']]); ?>" data-id="<?php echo $data['Food_ID']; ?>" class="modelButton">
			<div class="item">
	            <div class="inner-item">
		            <span><?php echo $data['Name']; ?></span>
		            <span class="small-text pull-right stars" alt="<?php echo $data['Rating']; ?>"><?php echo $data['Rating']; ?></span>
		            <span><p class="price"><?php echo 'RM'.$data['Price']; ?></p></span>
		            <p><?php echo $data['Description']; ?></p>
		          	<?php foreach($data['foodType']as $type): ?>
		            	<span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
		            <?php endforeach; ?>
	       		</div>
	            <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$data['PicPath']) ?></div>
	        </div>
		<?php endforeach ;?>
	</div>
</div>