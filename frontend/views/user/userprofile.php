<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\assets\UserAsset;
$this->title = 'My Profile';

UserAsset::register($this);
?>
<div class="profile">		
<?php 
  //user report modal
  Modal::begin([
      'header' => '<h2 class="modal-title">Report</h2>',
      'id'     => 'modal',
      'size'   => 'modal-sm',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
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
                    $picpath = Url::to(Yii::$app->params['userprofilepic'].$user->userdetails->User_PicPath);
                  else :
                    $picpath = Url::to('@web/imageLocation/DefaultPic.png');
                  endif ;
                endif ;
              ?>
            
              <?php echo Html::img($picpath,['class'=>"userprofile-image"])?>
              <?= Html::a('Edit', ['/user/userdetails'], ['class'=>'raised-btn btn-default userprofile-editbutton']) ?>
              <?= Html::a('Logout', ['/site/logout'], ['class'=>'raised-btn btn-danger userprofile-logoutbutton','data-method'=>'post']) ?>
          </div>
        </div>
        <div class="col-sm-9 userprofile-right">
          <h4><b>Details</b></h4>
          <div class="userprofile-input">
              <div class="row outer-row">
                <div class="inner-row">
                  <div class="userprofile-label">User Name:</div>
                  <div class="userprofile-text"><?php echo $user->username?></div>
                </div>
               </div>
              <div class="row outer-row">
			   <div class="inner-row">
                  <div class="userprofile-label">Full Name:</div>
                  <div class="userprofile-text"><?php echo $user->userdetails->fullname ?></div>
                </div>
                </div>
				 <div class="row outer-row">
                <div class="userprofile-label">Contact:</div>
                <div class="userprofile-text"><?php echo empty($user->userdetails->User_ContactNo) ? "not set" :$user->userdetails->User_ContactNo ?></div>
              </div>
              <div class="row outer-row">
                <div class="userprofile-label">Balance(RM): </div>
                <div class="userprofile-text"><?php echo $user->balance->User_Balance?></div>
              </div>
          </div>
        </div>
      </div>
    </div>
</div>
</div>
        
        