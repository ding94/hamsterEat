<?php
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
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
                <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">My Balance</a></li>
                <li role="presentation"><?php echo Html::a("Top Up",['/topup/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                <li role="presentation"><?php echo Html::a("Withdraw",['/withdraw/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
            </ul>
        </div>
        <div class="col-sm-8 userprofile-right">
             <div class="userprofile-input">
                <div class="row">
                  <div class="col-xs-4 userprofile-label">My Balance(RM) :</div>
                  <div class="col-xs-6 userprofile-text"><?php echo $model->User_Balance?></div>
                </div>
                <div class="row">
                  <div class="col-xs-4 userprofile-label">My Point :</div>
                  <div class="col-xs-6 userprofile-text"><?php echo $memberpoint->point ?></div>
                </div>
             </div>
             <div class="userprofile-address">
                <h4>Balance History</h4>
                <?php foreach($model->history as $history):?>
                    <div>
                      <?php echo $history->description ?>
                    </div>
                <?php endforeach ;?>  
             </div>
        </div>
    </div>
</div>

<?php 
Modal::begin([
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
Modal::end(); 
?>