<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\db\ActiveRecord;
use iutbay\yii2fontawesome\FontAwesome as FA;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Admin;
use frontend\assets\UserAsset;
use kartik\widgets\Select2;
$this->title = 'My Questions';
UserAsset::register($this);
?>

<div class="container" id="ticketh">
 
  <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
    </div>
  <div class="userprofile-detail">
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
                <ul class="nav nav-pills nav-stacked">
                    <li role="presentation"><?php echo Html::a("All",['/ticket/index'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation"><?php echo Html::a("Submit Ticket",['/ticket/submit-ticket'],['class'=>'btn-block userprofile-edit-left-nav'])?></li>
                    <li role="presentation" class="active"><a href="#" class="btn-block userprofile-edit-left-nav">Completed Ticket</a></li>
                </ul>
            </div>
        </div>
<div class="col-sm-8 right-side">
<p style="text-align:center;padding-top:20px;"><?php echo "Serial ID : " . $sid; ?></p>
  
  <div class="ticket-history">
      <table class="table table-inverse">
          <tr>
              <th>
                  <?php echo $name; ?> 
              </th>
              <th>
                   <?php echo $ticket->Ticket_Content; ?>
              </th>
              <th>
                  <?php echo date('d/M/Y h:i:s',($ticket->Ticket_DateTime)); ?>
              </th>
              <th><?php if(!empty($ticket->Ticket_PicPath)){ echo Html::a('View Picture',[Yii::$app->params['baseUrl'].$ticket->Ticket_PicPath],['target'=>'_blank']); }?></th>
          </tr>

        <?php foreach ($model as $k => $modell)  { ?> 
          <tr>
              <td data-th="Name">
                  <?php echo $modell->Replies_ReplyPerson;?> </td>
              </td>
        <td data-th="Enquiry">
        <?php echo $modell->Replies_ReplyContent; ?>
              <td data-th="Date">
                  <?php echo date('d/M/Y h:i:s',($modell->Replies_DateTime)); ?>
              </td>
              <td data-th="Refrences">
                
              <?php if(!empty($modell->Replies_PicPath)){ echo Html::a('View Picture',[Yii::$app->params['baseUrl'].$modell->Replies_PicPath],['target'=>'_blank']); }?>
              </td>
          </tr>
        <?php } ?>

      </table>

      <?php if ($ticket->Ticket_Status <3): ?>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($reply, 'Replies_ReplyContent')->textarea(['rows' => 6]) ?>
        <?= $form->field($upload, 'imageFile')->fileInput() ?>

        <div style="padding-left: 40%" class="form-group">
             <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            
        </div>
        <?php ActiveForm::end(); ?>
      <?php endif ?>
</div>
</div>
</div>
</div>

