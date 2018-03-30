<?php
use frontend\assets\RestaurantDefaultIndex2Asset;
use frontend\assets\StarsAsset;
use frontend\controllers\CartController;

StarsAsset::register($this);
RestaurantDefaultIndex2Asset::register($this);

 $imgdata =  $fooddata->multipleImg;
?>
<a href="<?php echo yii\helpers\Url::to(['/Food/default/detail','id'=>$fooddata['Food_ID'],'rid'=>$fooddata['Restaurant_ID']]); ?>" data-backdrop-limit="1" data-toggle="modal" data-target="#foodDetail"  data-img=<?php echo json_encode($imgdata)?> class="food-modal">
     
    <div class="item" data-id=<?php echo $fooddata->Food_ID ?>>
       
        <?php if (Yii::$app->formatter->asTime(time()) < date("11:0:0")):?>
            <div class="corner-ribbon top-left sticky red shadow"><span>-15%</span></div>
        <?php endif; ?>
            <div class="page-img">
                <img class="img" src=<?php echo $fooddata->singleImg?> alt="">
            </div>
            <div class="inner-item">
                <div class="foodName-div"><span class="foodName"><?php echo $fooddata['cookieName']; ?></span><span class="small-text stars" alt="<?php echo $fooddata['Rating']; ?>"><?php echo $fooddata['Rating']; ?></span></div>
                    <div class="price-div">
                        <?php if (Yii::$app->formatter->asTime(time()) < date("11:0:0")):?>
                            <span class="price">
                                <strike><?php echo 'RM'.$fooddata['Price']; ?></strike>
                                <?php $fooddata['Price']=$fooddata['Price']*0.85;$fooddata['Price'] = CartController::actionRoundoff1decimal($fooddata['Price']); echo 'RM'.number_format($fooddata['Price'],2); ?>
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