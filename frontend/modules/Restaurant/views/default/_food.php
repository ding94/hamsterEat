<?php
use frontend\assets\RestaurantDefaultIndex2Asset;
use frontend\assets\StarsAsset;
use frontend\controllers\CartController;

StarsAsset::register($this);
RestaurantDefaultIndex2Asset::register($this);

 $imgdata =  $fooddata->multipleImg;
?>
<?php if($fooddata['foodStatus']['food_limit'] <= 0): ?> 
<!-- if food limit below or equal to 0 render unclickable div with disable text overlay -->
<a href="#" title="">
<?php else:?>
<a href="<?php echo yii\helpers\Url::to(['/Food/default/detail','id'=>$fooddata['Food_ID'],'rid'=>$fooddata['Restaurant_ID']]); ?>" data-backdrop-limit="1" data-toggle="modal" data-target="#foodDetail"  data-img=<?php echo json_encode($imgdata)?> class="food-modal">
<?php endif; ?> 
    <div class="item" data-id=<?php echo $fooddata->Food_ID ?>>
        <?php if($fooddata['foodStatus']['food_limit'] <= 0): ?> 
        <div class="disable-div">Food Unavailable</div>
        <?php endif; ?> 
        <?php if (time() < strtotime(date("Y/m/d 11:0:0")) || $fooddata->promotion_enable == 1):?>
             <div class="corner-ribbon top-left sticky red shadow">
                    <span>
                        <?php echo $fooddata->promotion_enable == 0 ? "15%" : $fooddata->promotion_text;?>
                    </span>
            </div>
        <?php endif; ?>
            <div class="page-img">
                <img class="img" src=<?php echo $fooddata->singleImg?> alt="">
            </div>
            <div class="inner-item">
                <div class="foodName-div"><span class="foodName"><?php echo $fooddata['cookieName']; ?></span><span class="small-text stars" alt="<?php echo $fooddata['Rating']; ?>"><?php echo $fooddata['Rating']; ?></span></div>
                    <div class="price-div">
                       <?php if (time() < strtotime(date("Y/m/d 11:0:0"))|| $fooddata->promotion_enable == 1) :?>
                            <span class="price">
                                <strike><?php echo 'RM'.$fooddata['Price']; ?></strike>
                                <?php 
                                    $disPrice= $fooddata->promotion_enable == 0 ? $fooddata['Price']*0.85 : $fooddata->promotion_price;
                                    $disPrice = CartController::actionRoundoff1decimal($disPrice);
                                    echo 'RM'.number_format($disPrice,2); 
                                ?>
                            </span>
                        <?php else: ?>
                            <span class="price"><?php echo 'RM'.$fooddata['Price']; ?></span>
                        <?php endif;?>
                    </div>
                    <div class="rname-div"><span class="rname"><?php echo $fooddata['restaurant']['Restaurant_Name']; ?></span></div>
                    <div class="foodDesc"><span class="foodDesc">
                        <?php echo $fooddata['Description']; ?></span>
                    </div>
                    <div class="tag-div">
                        <?php foreach($fooddata['foodType']as $type): ?>
                        <span class="tag">
                            <?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
            </div>
    </div>
</a>
