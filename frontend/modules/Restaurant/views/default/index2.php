<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\food\Food;
use yii\bootstrap\Modal;
use common\models\food\Foodtype;
use kartik\widgets\ActiveForm;
use frontend\assets\StarsAsset;
use frontend\assets\RestaurantDefaultIndex2Asset;
use yii\widgets\LinkPager;
use yii\data\Pagination;
$this->title = "Available Restaurants";

StarsAsset::register($this);
RestaurantDefaultIndex2Asset::register($this);
?>
<style>

</style>
 <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<div class="container" id="group-area-index2">
    <h1>Order Food for Delivery</h1>
  
        <?php echo Html::a('<i class="fa fa-home"> Restaurant</i>', ['index', 'groupArea'=>$groupArea], ['class'=>'btn btn-default']);?>
        <input type="checkbox" id="sidebartoggler" name="" value="">
        <div class="page-wrap">

			  <label for="sidebartoggler" class="toggle">Filter</label>
		<!--<a href="#top" title="Go to top of page"><span><i class="fa fa-chevron-up fa-2x" aria-hidden="true"></i></span>-->
		<a href="#top" class="scrollToTop"></a>
            <div class="filter">
                <div class="filter container">
                    <div class="input-group">
                    <?php $form = ActiveForm::begin(['id' => 'form-searchfood']) ?>
                    <?= $form->field($search, 'Nickname',['addon'=>['append'=>['content'=>Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn btn-default icon-button', 'name' => 'search-button2']),'asButton'=>true]]])->textInput(['placeholder' => "Search Food"])->label(false); ?>
                    <?php ActiveForm::end(); ?>
                    </div>
                    <div class ="filter-name">
                        <p><i class="fa fa-sliders"> Filter By</i></p>
                    </div>
                    <ul class ="filter-list">
                    <?php echo Html::a('<li>All</li>', ['show-by-food', 'groupArea'=>$groupArea])."&nbsp;&nbsp;"; ?>  
                        <?php foreach ($types as $types) :
                        echo Html::a('<li>'.$types['Type_Desc'].'</li>', ['food-filter', 'groupArea'=>$groupArea ,'typefilter'=>$types['ID']])."&nbsp;&nbsp;";
                        endforeach; ?>
                    </ul> 
                </div>
            </div>
        </div>
       
    
    <br>
  
    <?php if ($mode == 2)
    {
        $foodtype = Foodtype::find()->where('ID = :id', [':id'=>$filter])->one();
        echo "<h3>Filtering By ".$foodtype['Type_Desc']."</h3>";
    }
    elseif ($mode == 3)
    {
        echo "<h3>Showing results similar to ".$keyword."</h3>";
    }
    elseif ($mode == 4)
    {
        $foodtype = Foodtype::find()->where('ID = :id', [':id'=>$filter])->one();
        echo "<h3>Showing results similar to ".$keyword." with filter ".$foodtype['Type_Desc']."</h3>";
    }

    ?>
    <div class="outer-container">
        <div class="menu-container">
        <?php foreach($restaurant as $data) : 
            if ($mode == 1)
            {
                $fooddata=food::find()->where('Restaurant_ID=:id', [':id' => $data['Restaurant_ID']])->joinWith(['foodStatus'=>function($query){
                    $query->where('Status = 1');
                }]); 
                var_dump($fooddata->count());exit;
                $pagination = new Pagination(['totalCount'=>$fooddata->count(),'pageSize'=>1]);
                $fooddata = $fooddata->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();
            }
            elseif ($mode == 2)
            {
                $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status and Type_ID = :tid', [':id' => $data['Restaurant_ID'], ':status'=> 1, ':tid'=>$filter])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
            }
            elseif ($mode == 3)
            {
                $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status', [':id' => $data['Restaurant_ID'], ':status'=> 1])->andWhere(['like', 'Name', $keyword])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
            }
            elseif ($mode == 4)
            {
                $fooddata=food::find()->where('Restaurant_ID=:id and Status = :status and Type_ID = :tid', [':id' => $data['Restaurant_ID'], ':status'=> 1, ':tid'=>$filter])->andWhere(['like', 'Name', $keyword])->innerJoinWith('foodType',true)->innerJoinWith('foodStatus',true)->all();
            }

            Modal::begin([
                'id'     => 'foodDetail',
                'size'   => 'modal-lg',
                // 'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
            ]);
                    
            Modal::end(); ?>

            <?php foreach($fooddata as $fooddata) : ?>
                <a href="<?php echo yii\helpers\Url::to(['/food/food-details','id'=>$fooddata['Food_ID'],'rid'=>$fooddata['Restaurant_ID']]); ?>" data-toggle="modal" data-target="#foodDetail"  data-img="<?php echo $fooddata['PicPath'];?>">
                    <div class="item">
                        <div class="img"><?php echo Html::img('@web/imageLocation/foodImg/'.$fooddata['PicPath']) ?></div>
                        <div class="inner-item">
                            <span class="foodName"><?php echo $fooddata['Name']; ?></span>
                            <span class="small-text pull-right stars" alt="<?php echo $fooddata['Rating']; ?>"><?php echo $fooddata['Rating']; ?></span>
                            <span><p class="price"><?php echo 'RM '.$fooddata['Price']; ?></p></span>
                            <span><p class="rname"><?php echo $data['Restaurant_Name']; ?></p></span>
                            <p class="foodDesc"><?php echo $fooddata['Description']; ?></p>
                            <?php foreach($fooddata['foodType']as $type): ?>
                            <span class="tag"><?php echo $type['Type_Desc'].'&nbsp;&nbsp;&nbsp;'; ?></span>
                            <?php endforeach; ?>
                        </div>
                       
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </div>
    </div>
    <?php echo LinkPager::widget([
          'pagination' => $pagination,
          ]); ?>
</div>
