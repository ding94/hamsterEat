<?php
/* @var $this yii\web\View */

use common\models\Restaurant;
use yii\helpers\Html;
use frontend\assets\DeliverymanOrdersHistoryAsset;
use kartik\widgets\Select2;
use yii\widgets\LinkPager;
use kartik\date\DatePicker;
use kartik\widgets\ActiveForm;

$this->title = "Deliveryman Order's History";
DeliverymanOrdersHistoryAsset::register($this);
?>
<div class="container" id="deliveryman-orders-history-container">
    <div class="deliveryman-orders-history-header">
        <div class="deliveryman-orders-history-header-title"><?= Html::encode($this->title) ?></div>
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
                            'placeholder' => 'Go To ...',
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
                <ul id="deliveryman-orders-nav" class="nav nav-pills nav-stacked">
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="Deliveryman Orders History" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                        </li>
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="deliveryman-orders-history-content" class="col-sm-10">
            <?php $form = ActiveForm::begin(['method' => 'get','action'=>['history']]); ?>
                <div class="search-border">
                    <label class="control-label">Search Data</label>
                    <div class="row">
                        <div class="col-sm-6">
                            <?php echo $form->field($searchModel, 'keyWordStatus')->widget(Select2::classname(), [
                                        'data' => $searchModel->keyWordArray,
                                        'options' => [ 'placeholder' => 'Select Delivery ID',],
                                        'pluginOptions' => [
                                                'allowClear' => true
                                    ],
                                ]);
                            ?>
                        </div>
                        <div class="col-sm-6">
                             <?php echo $form->field($searchModel, 'keyWord');?>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-sm-6">
                            <?php echo $form->field($searchModel, 'statusType')->widget(Select2::classname(), [
                                    'data' => [1=>'Find Delivery Status',2=>'Find Order Status'],
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
                                <?= Html::submitButton('Search', ['class' => 'btn-block raised-btn main-btn']) ?>
                            </div>
                            <div class="col-md-6">
                                 <?= Html::a('Reset', ['history'],['class' => 'btn-block raised-btn ']) ?>
                            </div>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
            <br>
            <?php if(empty($dman)) :?>
                 <h2>There are no orders currently...</h2>
            <?php else :
                foreach ($dman as $did => $data) : 
                    $order = $data['order'];
                    $items = $data['item'];
            ?>
            <table class="table table-user-info deliveryman-orders-history-table">
                <thead class="needed">
                    <tr>
                        <th class="center" colspan="6" data-th="Delivery ID">Delivery ID : <?= $order['Delivery_ID']?></th>
                       
                    </tr>
                    <tr >
                        <th class="mobile-same-col" colspan="2" data-th="User Name">User Name : <?= $order['User_Username']?></th>
                        <th class="mobile-same-col" colspan="2" data-th="Price">Price : RM<?= $order['Orders_TotalPrice']?></th>
                        <th class="mobile-same-col" colspan="2" data-th="Delivery Status">Status : <?= $statusid[$order['Orders_Status']]?></th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th>Restaurant Name</th>
                        <th>Restaurant Address</th>
                        <th>Order ID</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($items as $rid=> $restaurant):
                            $rowspan = count($restaurant);
                            $res = Restaurant::findOne($rid);
                            foreach($restaurant as $i=>$item):
                    ?>
                        <tr>
                            <?php if($i == 0) :?>
                                <td data-th="Restaurant Name" rowspan=<?= $rowspan ?>><?= $res->Restaurant_Name ?></td>
                                <td data-th="Restaurant Address" rowspan=<?= $rowspan ?>><?= $res->fulladdress ?></td>
                            <?php endif?>
                            <td class="border" data-th="Order ID" ><?= $item['Order_ID']?></td>
                       
                            <td class="border" data-th="Quantity" ><?= $item['OrderItem_Quantity']?></td>
                            <td class="border" data-th="Order Status" ><?= $statusid[$item['OrderItem_Status']]?></td>
                        </tr>
                    <?php 
                            endforeach;
                        endforeach;
                    ?>
                </tbody>
              
            </table>
            <?php 
                endforeach; 
            endif;
            ?>
              <?php echo LinkPager::widget([
                'pagination' => $pagination,
              ]); ?>
        </div>
      
    </div>
</div>