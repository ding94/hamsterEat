<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\widgets\LinkPager;
use yii\helpers\Json;
use kartik\widgets\ActiveForm;
use common\models\food\Foodselectiontype;
use kartik\date\DatePicker;
use frontend\assets\RestaurantOrdersHistoryAsset;

$this->title = $title;
RestaurantOrdersHistoryAsset::register($this);
?>
<div id="restaurant-orders-history-container" class = "container">
    <div class="restaurant-orders-history-header">
        <div class="restaurant-orders-history-header-title"><?= Html::encode($this->title) ?></div>
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
                <ul id="restaurant-orders-history-nav" class="nav nav-pills nav-stacked">
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="Restaurant Orders History" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo Yii::t('m-restaurant',$name) ?></a>
                        </li>   
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="restaurant-orders-history-content" class="col-sm-10">
            <?php $form = ActiveForm::begin(['method' => 'get','action'=>['history','rid'=>$rid]]); ?>
                    <div class="border">
                        <label class="control-label"><?= Yii::t('m-restaurant','Search Data')?></label>
                        <div class="row margin-bottom">
                            <div class="col-sm-6">
                                <?php echo $form->field($searchModel, 'keyWordStatus')->widget(Select2::classname(), [
                                            'data' => $searchModel->keyWordArray,
                                            'options' => [ 'placeholder' => Yii::t('m-restaurant','Select Delivery ID'),],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ])->label(Yii::t('m-restaurant','Key Word').' '.Yii::t('common','Status'));
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php echo $form->field($searchModel, 'keyWord')->label(Yii::t('m-restaurant','Key Word'));?>
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-sm-6">
                                <?php echo $form->field($searchModel, 'statusType')->widget(Select2::classname(), [
                                            'data' => [1=>Yii::t('m-restaurant','Find Delivery Status'),2=>Yii::t('m-restaurant','Find Order Status')],
                                            'hideSearch' => true,
                                        ])->label(false);
                                ?>
                            </div>
                            <div class="col-sm-6">
                                <?php echo $form->field($searchModel, 'status')->widget(Select2::classname(), [
                                            'data' => $status,
                                            'options' => [ 'placeholder' => 'Select Delivery Or Order Status',],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ])->label(false);
                                ?>
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-md-12">
                                <?php echo DatePicker::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'first',
                                        'attribute2' => 'last',
                                        'type' => DatePicker::TYPE_RANGE,
                                        'form' => $form,
                                        'pluginOptions' => [
                                            'format' => 'yyyy-mm-dd',
                                            'autoclose' => true,
                                        ]
                                    ]);
                                ?>
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-md-6">
                                <?= Html::submitButton(Yii::t('common','Search'), ['class' => 'btn-block raised-btn main-btn']) ?>
                            </div>
                            <div class="col-md-6">
                                 <?= Html::a(Yii::t('common','Reset'), ['history','rid'=>$rid],['class' => 'btn-block raised-btn ']) ?>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                 <br>
            <?php if(empty($result)) :?>
                 <h2><?= Yii::t('m-restaurant','There are no orders currently')?>...</h2>
            <?php else :?>
                <?php foreach ($result as $did =>$delivery) : 
                        $deliveryData = array_shift($delivery);
                ?>  
                <table class="table table-user-info table-hover" style="border:1px solid black;">
                    <thead id="thead-needed">
                        <tr>
                            <th colspan = '2' data-th="Delivery_ID">
                                <center><?= Yii::t('common','Delivery ID')?>: <?= $did?> 
                            </th>
                            <th colspan= '2' data-th="Delivery Status">
                                <center> <?= $deliveryData['DateTime'] ?>
                            </th>
                            <th colspan= '1' data-th="Delivery Status">
                                <center><?= Yii::t('common','Status')?>: <?= $statusid[$deliveryData['status']] ?>
                            </th> 
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th width="10%"><?= Yii::t('order','Order ID')?></th>
                            <th width="30%"><?= Yii::t('order','Food Name')?> </th>
                            <th>Selection </th>
                            <th width="10%" class="center"><?= Yii::t('order','Quantity')?> </th>
                            <th width="15%" class="center"><?= Yii::t('common','Status')?> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($delivery as $order): ?>
                        <tr>
                            <td data-th="Order ID"><?= $order->Order_ID?></td>
                            <td data-th="Food Name"><?= $order->food['Name']?></td>
                            <?php 
                                $selectionName = Json::decode($order->trim_selection);
                                if(empty($selectionName)):
                                    $name = Yii::t('common',"empty");
                                else :
                                    $name ="";
                                    foreach($selectionName as $i=> $selection) :
                                        $type = Foodselectiontype::findOne($i);

                                        if(empty($name)):
                                            $name =$type->TypeName .': '. $selection['name'] . ' ';
                                        else :
                                            $name .= "&nbsp; | ".$type->TypeName .': '.$selection['name'] ;
                                        endif ;
                                    endforeach ;
                                endif;
                            ?>
                            <td data-th="Selection"><?=$name ?></td>
                            <td data-th="Quantity" class="center"><?= $order->OrderItem_Quantity?></td>
                            <td data-th="Status" class="center"><?= $statusid[$order->OrderItem_Status]?></td>
                        </tr>
                        <?php endforeach ;?>   
                    </tbody>
                </table>
                <?php endforeach;?>
        <?php endif ;?>
        <?php echo LinkPager::widget([
          'pagination' => $pagination,
          ]); ?>
        </div>
    </div>
</div>