<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\assets\UserAsset;
/* @var $this yii\web\View */
UserAsset::register($this);
?>

<div id="userprofile" class="row">
   <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
    <div class="userprofile-detail">
        <div class="col-sm-2 ">
           <ul class="nav nav-pills nav-stacked">
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Balance history</a></li>
                <li role="presentation"><?php echo Html::a("Top Up",['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                <li role="presentation"><?php echo Html::a("Withdraw",['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
            </ul>
        </div>
        <div class="col-sm-10 account-history">

            <table class="table table-hover">
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
                    <td data-th="Time"><?php echo $data->created_at?></td>
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