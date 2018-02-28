<?php

/* @var $this yii\web\View */
// use dosamigos\chartjs\ChartJs;
use kartik\date\DatePicker;
use kartik\widgets\{Select2,ActiveForm};
use yii\grid\GridView;
use yii\helpers\Html;
use frontend\assets\RestaurantStatisticsAsset;

$this->title = Yii::t('m-restaurant',"View Statistics");
$rid = Yii::$app->request->get('rid');
RestaurantStatisticsAsset::register($this); 
?>
<div class="container">
</div>

<div id="restaurant-statistics-container" class="container">
    <div class="restaurant-statistics-header">
        <div class="restaurant-statistics-header-title"><?= Html::encode($this->title) ?></div>
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
                <ul id="restaurant-statistics-nav" class="nav nav-pills nav-stacked">
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="View Statistics" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-restaurant',$name)?></a>
                        </li>   
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="restaurant-statistics-content" class="col-sm-10">
        	<?php $form = ActiveForm::begin(['method' => 'get','action'=>['statistics/index']]); ?>
		    <input type="hidden" name="rid" value="<?php echo $rid; ?>">
		    <label class="control-label"><?= Yii::t('m-restaurant','Select Date') ?></label>
		    <div class="row">
		        <div class="col-md-6">
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
		            <?= Html::submitButton(Yii::t('m-restaurant','Filter'), ['class' => 'btn-block raised-btn main-btn']) ?>
		        </div>
		     </div>
		<?php ActiveForm::end(); ?>
		<br>
		<?php echo GridView::widget([
		        'dataProvider'=>$provider,
		        'columns'=>[
		            'Food Name',
		            'Quantity Sold',
		        ],
		        'layout' => '{items}{summary}{pager}',
				'emptyText' => '-',
		    ]);
		?>
        </div>
    </div>
</div>