<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\assets\UserAsset;
use common\models\company\Company;
$this->title = Yii::t('user','My Profile');

UserAsset::register($this);
?>
<div class="profile">		
<?php 
  //user report modal
  Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('common','Report').'</h2>',
      'id'     => 'modal',
      'size'   => 'modal-sm',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('common','Close').'</a>',
  ]);
  Modal::end();
    //new address modal
  Modal::begin([
      //'header' => '<h2 class="modal-title">New Address</h2>',
      'id'     => 'address-modal',
      'size'   => 'modal-xs',
      //'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
  ]);
  Modal::end();
?>


  <div id="userprofile" class="row">
      <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
      </div>
      <div class="userprofile-detail">
        <div class="col-sm-3 userprofile-left">
          <div class="userprofile-avatar">
              <?php 
              
                if(is_null($user->userdetails->User_PicPath)) :

                  $picpath = Url::to('@web/imageLocation/DefaultPic.png');
                else :
                  if(file_exists(Yii::$app->params['userprofilepic'].$user->userdetails->User_PicPath)) :
                     $picpath = Url::to("@web/".Yii::$app->params['userprofilepic'].$user->userdetails->User_PicPath);
                  else :
                    $picpath = Url::to('@web/imageLocation/DefaultPic.png');
                  endif ;
                endif ;
              ?>
            
              <?php echo Html::img($picpath,['class'=>"userprofile-image"])?>
              <?= Html::a(Yii::t('common','Edit'), ['/user/userdetails'], ['class'=>'raised-btn btn-default userprofile-editbutton']) ?>
              <?= Html::a(Yii::t('common','Logout'), ['/site/logout'], ['class'=>'raised-btn btn-danger userprofile-logoutbutton','data-method'=>'post']) ?>
              <?php $authAuthChoice = yii\authclient\widgets\AuthChoice::begin([
                    'baseAuthUrl' => ['site/link'],
                    ]); ?>
                        <?php foreach ($authAuthChoice->getClients() as $client): ?>
                          <?= $authAuthChoice->clientLink($client,
                                '<span class="fa fa-'.$client->getName().'"></span> Link with '.$client->getTitle(),
                                [
                                    'class' => 'btn btn-block btn-social btn-'.$client->getName(),
                                    ]) ?>
                                <?php endforeach; ?>
              <?php yii\authclient\widgets\AuthChoice::end(); ?>
          </div>
        </div>

        <div class="col-sm-5 userprofile-right">
          <h4><b><?= Yii::t('common','Details') ?></b></h4>
          <div class="userprofile-input">
              <div class="row outer-row">
                <div class="inner-row">
                  <div class="userprofile-label"><?= Yii::t('user','User Name')?>:</div>
                  <div class="userprofile-text"><?php echo $user->username?></div>
                </div>
               </div>
              <div class="row outer-row">
			   <div class="inner-row">
                  <div class="userprofile-label"><?= Yii::t('user','Full Name')?>:</div>
                  <div class="userprofile-text"><?php echo $user->userdetails->fullname ?></div>
                </div>
                </div>
				 <div class="row outer-row">
          <div class="inner-row">
                <div class="userprofile-label"><?= Yii::t('common','Contact No')?>:</div>
                <div class="userprofile-text"><?php echo empty($user->userdetails->User_ContactNo) ? Yii::t('common',"not set") :$user->userdetails->User_ContactNo ?></div>
                </div>
              </div>
              <div class="row outer-row">
                <div class="inner-row">
                <div class="userprofile-label"><?= Yii::t('common','Balance')?>(RM): </div>
                <div class="userprofile-text"><?php echo $user->balance->User_Balance?></div>
                </div>
              </div>
          </div>
        </div>
        <?php if(empty(!$employee)): ?>
          <div class='col-sm-3 userprofile-right' style="float: left;">
            <?php if(!empty($company = Company::find()->where('owner_id=:id',[':id'=>Yii::$app->user->id])->all())): ?>
              <?= Yii::t('user','You are Owner of').' '; ?> <br>
              <?php foreach($company as $k => $c) : ?>
                <H4><b><?= ($k+1).'. '.$c['name']; ?></b></H4>
              <?php endforeach; ?>
            <?php elseif($employee['status'] == 1) :?>
              <?= Yii::t('user','You are employee of').' ';?> <br>
              <H4><b><?= $employee['company']['name']; ?> !</b></H4>
            <?php else: ?>
              <H4><b><?= Yii::t('user','Company Register In Process')?></b></H4>
            <?php endif;?>
            </div>
        <?php endif;?>
      </div>
    </div>
</div>
</div>
        
        