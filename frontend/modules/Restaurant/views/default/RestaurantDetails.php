<?php
use yii\helpers\Html;
?>

<div>
    <?php $picpath = $id['Restaurant_RestaurantPicPath'];

        if (is_null($id['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
        echo '<th rowspan = "4">' ?> <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>"; ?>
    <h1><center><?php echo $id['Restaurant_Name']; ?></h1>
    <hr>


</div>