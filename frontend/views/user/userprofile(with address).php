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
      'header' => '<h2 class="modal-title">New Address</h2>',
      'id'     => 'address-modal',
      'size'   => 'modal-md',
      'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
  ]);
  Modal::end();
  // edit address modal
  Modal::begin([
        'header' => '<h2 class="modal-title">Edit Address</h2>',
        'id'     => 'edit-address-modal',
        'size'   => 'modal-md',
        'footer' => '<a href="#" class="raised-btn alternative-btn" data-dismiss="modal">Close</a>',
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
              <?= Html::a('Edit', ['/user/userdetails'], ['class'=>'raised-btn btn-default userprofile-editbutton']) ?>
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

          <div class="userprofile-address">
            <?php $count = count($user->address)?>
            <?php echo $count < 3 ? Html::a("Add New Address",['/user/newaddress'],['class' => 'raised-btn main-btn add-new-address-btn','data-toggle'=>'modal','data-target'=>'#address-modal']) : ""?>
        
              <?php if(empty($user->address)) :?>
                  <h4>Empty Address</h4>
              <?php else : ?>
              <table class="table table-hover my-address">
                <thead>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 20%">Primary</th>
                    <th style="width: 65%">Address</th>
                    <th style="width: 10%">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($user->address as $i=>$address):?>
                    <tr>
                      <td><?php echo $i+1?></td>
                      <td><?php echo $address->level == 1 ? '<span class="primary">Primary</span>': Html::a('<i class="fa fa-check"></i> Mark as Primary',['/user/primary-address','id' => $address->id],['class'=>'btn btn-block primary-btn'])?></td>
                      <td>
                        <?php echo $address->FullAddress?>
                        <br><br>
                        <strong>Name: </strong><?= $address['recipient']; ?>
                        <br>
                        <strong>Contact No: </strong><?= $address['contactno']; ?></td>
                      <td>
                        <div class="row address-button">
                          <div class="col-xs-6">
                            <?php echo Html::a("<span class='glyphicon glyphicon-pencil userprofile-pencil' title='edit'></span>",['/user/edit-address','id'=> $address->id],['data-toggle'=>'modal','data-target'=>'#edit-address-modal'])?>
                          </div>
                          <div class="col-xs-6">
                            <?php echo Html::a("<span class='glyphicon glyphicon-trash userprofile-trash' title='delete'></span>",['/user/delete-address','id'=> $address->id] ,['data' => ['confirm' => 'Are You Sure Want to Delete Address','method' => 'post']] )?>    
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
        
        