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
<html>
</div>
<div class="site-contact">
    <div class="tableHeader">
        <ul>
            <li class="hover">
                <a href="index.php?r=ticket/submit-ticket">Create Ticket </a>
            </li>
            <li>
                <a href="index.php?r=ticket/index"> Ticket In Process</a>
            </li>
            <li>
                <a href="index.php?r=ticket/completed">Completed</a>
            </li>
        </ul>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <h4><?php echo "Serial ID : " . $sid; ?></h4>

    <br>
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
              <th><?php if(!empty($ticket->Ticket_PicPath)){ echo Html::a('Picture',Yii::$app->urlManager->baseUrl.'/'.$ticket->Ticket_PicPath,['target'=>'_blank']); }?></th>
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
              <?php if(!empty($modell->Replies_PicPath)){ echo Html::a('Picture',Yii::$app->urlManager->baseUrl.'/'.$modell->Replies_PicPath,['target'=>'_blank']); }?>
              </td>
          </tr>
        <?php } ?>

      </table>

      <?php if ($ticket->Ticket_Status <3): ?>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($reply, 'Replies_ReplyContent')->textarea(['rows' => 6]) ?>
        <?= $form->field($upload, 'imageFile')->fileInput() ?>

        <div class="form-group">
             <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
      <?php endif ?>

</div>
</html>