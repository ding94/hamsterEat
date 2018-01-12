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
                <ul id="restaurant-orders-history-nav" class="nav nav-pills nav-stacked">
                    <?php foreach($link as $url=>$name):?>
                        <li role="presentation" class=<?php echo $name=="Restaurant Orders History" ? "active" :"" ?>>
                            <a class="btn-block" href=<?php echo $url?>><?php echo $name?></a>
                        </li>   
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>
        <div id="restaurant-orders-history-content" class="col-sm-10">
            <?php $form = ActiveForm::begin(['method' => 'get','action'=>['history','rid'=>$rid]]); ?>
                    <div class="border">
                        <label class="control-label">Search Data</label>
                        <div class="row margin-bottom">
                            <div class="col-md-4">
                                <?php 
                                    echo Select2::widget([
                                        'name' => 'search[did]',
                                        'value' => $arrayData['select']['did'],
                                        'data' => $arrayData['did'],
                                        'options' => [
                                            'placeholder' => 'Select Delivery ID',
                                            //'multiple' => true
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-4">
                                 <?php 
                                    echo Select2::widget([
                                        'name' => 'search[oid]',
                                        'value' => $arrayData['select']['oid'],
                                        'data' => $arrayData['oid'],
                                        'options' => [
                                            'placeholder' => 'Select Order ID',
                                            //'multiple' => true
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-4">
                                 <?php 
                                    echo Select2::widget([
                                        'name' => 'search[fid]',
                                        'value' => $arrayData['select']['fid'],
                                        'data' => $arrayData['fid'],
                                        'options' => [
                                            'placeholder' => 'Select Food Name',
                                            //'multiple' => true
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                ?>
                           
                            </div>
                        </div>
                        <div class="row margin-bottom">
                            <div class="col-md-12">
                                <?php
                                    echo DatePicker::widget([
                                        'name' => 'search[first]',
                                        'value' => $arrayData['select']['first'],
                                        'type' => DatePicker::TYPE_RANGE,
                                        'name2' => 'search[last]',
                                        'value2' => $arrayData['select']['last'],
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'yyyy-m-d'
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
                                 <?= Html::a('Reset', ['history','rid'=>$rid],['class' => 'btn-block raised-btn ']) ?>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
                 <br>
            <?php if(empty($result)) :?>
                 <h2>There are no orders currently...</h2>
            <?php else :?>
                <?php foreach ($result as $did =>$delivery) : 
                        $deliveryStatus = array_shift($delivery);
                ?>  
                <table class="table table-user-info table-hover" style="border:1px solid black;">
                    <thead id="thead-needed">
                        <tr>
                            <th colspan = '3' data-th="Delivery_ID">
                                <center>Delivery ID: <?= $did?> 
                            </th>
                            <th colspan= '2' data-th="Delivery Status">
                                <center>Status: <?= $statusid[$deliveryStatus] ?>
                            </th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th width="10%">Order ID</th>
                            <th width="30%">Food Name </th>
                            <th>Selection </th>
                            <th width="10%" class="center">Quantity </th>
                            <th width="15%" class="center">Status </th>
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
                                    $name = "empty";
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