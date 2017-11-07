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

    $this->title = 'My Questions';
?>

<div class="container" style="background-color:white;">
 <a class="back" href="../web/index.php?r=ticket%2Findex"><i class="fa fa-angle-left">&nbsp;Back</i></a><br>
	<h1 class="col-md-6 col-md-offset-3" style="text-align:center;"><?= Html::encode($this->title) ?></h1><br>
    <h4 class="col-md-6 col-md-offset-3" style="text-align:center;"><?php echo "Serial ID : " . $sid; ?></h4>
    <br>
    <div class="col-md-8 col-md-offset-2">
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
              <th><?php if(!empty($ticket->Ticket_PicPath)){ echo Html::a('View Picture',Yii::$app->urlManager->baseUrl.'/'.$ticket->Ticket_PicPath,['target'=>'_blank']); }?></th>
          </tr>

        <?php foreach ($model as $k => $modell)  { ?> 
          <tr>
              <td>
                  <?php echo '<p>'.$modell->Replies_ReplyPerson.' </td><td>'.$modell->Replies_ReplyContent.'</p>'; ?>
              </td>
              <td>
                  <?php echo date('d/M/Y h:i:s',($modell->Replies_DateTime)); ?>
              </td>
              <td>
              <?php if(!empty($modell->Replies_PicPath)){ echo Html::a('View Picture',Yii::$app->urlManager->baseUrl.'/'.$modell->Replies_PicPath,['target'=>'_blank']); }?>
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
