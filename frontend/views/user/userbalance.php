<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\assets\TopupWithdrawMpHistoryAsset;

TopupWithdrawMpHistoryAsset::register($this);
?>

<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="topup-detail">
        <div class="col-sm-2 ">
           <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Balance history</a></li>
                <li role="presentation"><?php echo Html::a("Top Up",['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                <li role="presentation"><?php echo Html::a("Withdraw",['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
            </ul>
        </div>
        <div class="col-sm-10 right-side">
            <div  id="balance-history" class="history-container">
              <table class="table table-user-information" id="display">
                <tr>
                    <td id="right" class="history-font"><?php echo Html::encode($this->title)?></td> 
                    <td id="middle" class="link history-font"><?php echo Html::a("Top Up History",['/topup-history/index'],['class'=>'btn-block remove-all'])?></td>
                    <td id="left" class="link history-font"><?php echo Html::a("Withdraw History",['/withdraw-history/index'],['class'=>'btn-block remove-all'])?></td>
                </tr>  
              </table>
            </div> 
            <div class="account-history">
              <table class="table table-hover border">
                <thead>
                  <tr>
                    <th>Time</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Amount(RM)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($history as $data):?>
                    <tr>
                      <td data-th="Time"><?php echo Yii::$app->formatter->asDatetime($data->created_at, "php:d-m-Y H:i:s")?></td>
                      <td data-th="Decription"><?php echo $data->description?></td>
                      <td data-th="Type"><?php echo $data->type == 0 ? "Minus" : "Postive" ?></td>
                      <td data-th="Amount(RM)"><?php echo $data->amount?></td>
                    </tr>
                  <?php endforeach ;?> 
                </tbody>
              </table>
             
              <?php echo LinkPager::widget([
                  'pagination' => $historypagination,
              ]); ?>
            </div>
        </div>
    </div>
</div>

<?php 
/*Modal::begin([
'id' => 'reason-modal',
'header' => '<h4 class="modal-title">Reject Reason</h4>',
'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]); 
$requestUrl = Url::toRoute('user/rejectreason');
$js = <<<JS
$.get('{$requestUrl}', {},
function (data) {
$('.modal-body').html(data);
} 
);
JS;
$this->registerJs($js);
Modal::end(); */
?>