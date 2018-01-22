<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\assets\UserAsset;
$this->title = Yii::t('user','My Profile');

UserAsset::register($this);
?>
<div class="profile">		
<?php 
  //user report modal
  Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('user','Report').'</h2>',
      'id'     => 'modal',
      'size'   => 'modal-sm',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('user','Close').'</a>',
  ]);
  Modal::end();
    //new address modal
  Modal::begin([
      'header' => '<h2 class="modal-title">'.Yii::t('user','New Address').'</h2>',
      'id'     => 'address-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('user','Close').'</a>',
  ]);
  Modal::end();
  // edit address modal
  Modal::begin([
        'header' => '<h2 class="modal-title">'.Yii::t('user','Edit Address').'</h2>',
        'id'     => 'edit-address-modal',
        'size'   => 'modal-md',
        'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">'.Yii::t('user','Close').'</a>',
  ]);
  Modal::end() 
?>


  <div id="userprofile" class="row">
      <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
      </div>
      <div class="userprofile-detail">
        <div class="col-sm-3 userprofile-left">
          <div class="userprofile-avatar">
              <?php $picpath = is_null($user->userdetails->User_PicPath) ? Url::to('@web/imageLocation/Default.png'): Url::to('@web/'.$user->userdetails->User_PicPath); ?>
              <?php echo Html::img($picpath,['class'=>"userprofile-image"])?>
              <?= Html::a(Yii::t('user','Edit'), ['/user/userdetails'], ['class'=>'raised-btn btn-default userprofile-editbutton']) ?>
          </div>
        </div>
        <div class="col-sm-9 userprofile-right">
          <h4><b><?= Yii::t('user','Details')?></b></h4>
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
                <div class="userprofile-label"><?= Yii::t('user','Contact')?>:</div>
                <div class="userprofile-text"><?php echo empty($user->userdetails->User_ContactNo) ? "not set" :$user->userdetails->User_ContactNo ?></div>
              </div>
              <div class="row outer-row">
                <div class="userprofile-label"><?= Yii::t('user','Balance')?>(RM): </div>
                <div class="userprofile-text"><?php echo $user->balance->User_Balance?></div>
              </div>
          </div>

          <div class="userprofile-address">
            <?php $count = count($user->address)?>
            <?php echo $count < 3 ? Html::a(Yii::t('user','Add New Address'),['/user/newaddress'],['class' => 'raised-btn main-btn add-new-address-btn','data-toggle'=>'modal','data-target'=>'#address-modal']) : ""?>
        
              <?php if(empty($user->address)) :?>
                  <h4><?= Yii::t('user','Empty Address')?></h4>
              <?php else : ?>
              <table class="table table-hover my-address">
                <thead>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 20%"><?= Yii::t('user','Primary')?></th>
                    <th style="width: 65%"><?= Yii::t('user','Address')?></th>
                    <th style="width: 10%"><?= Yii::t('user','Action')?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($user->address as $i=>$address):?>
                    <tr>
                      <td><?php echo $i+1?></td>
                      <td><?php echo $address->level == 1 ? '<span class="primary">'.Yii::t('user','Primary').'</span>': Html::a('<i class="fa fa-check"></i> '.Yii::t('user','Mark as Primary'),['/user/primary-address','id' => $address->id],['class'=>'btn btn-block primary-btn'])?></td>
                      <td>
                        <?php echo $address->FullAddress?>
                        <br><br>
                        <strong><?= Yii::t('user','Name')?>: </strong><?= $address['recipient']; ?>
                        <br>
                        <strong><?= Yii::t('user','Contact No')?>: </strong><?= $address['contactno']; ?></td>
                      <td>
                        <div class="row address-button">
                          <div class="col-xs-6">
                            <?php echo Html::a("<span class='glyphicon glyphicon-pencil userprofile-pencil' title='edit'></span>",['/user/edit-address','id'=> $address->id],['data-toggle'=>'modal','data-target'=>'#edit-address-modal'])?>
                          </div>
                          <div class="col-xs-6">
                            <?php echo Html::a("<span class='glyphicon glyphicon-trash userprofile-trash' title='delete'></span>",['/user/delete-address','id'=> $address->id] ,['data' => ['confirm' => Yii::t('user','Are You Sure Want to Delete Address'),'method' => 'post']] )?>    
                          </div>
                        </div>        
                      </td>
                    </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <?php endif;?>
          </div>
        </div>
      </div>
    </div>
</div>
</div>
        
        