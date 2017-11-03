<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use frontend\assets\UserAsset;
$this->title = 'My Profile';

UserAsset::register($this);
?>
		
    <?php Modal::begin([
            'header' => '<h2 class="modal-title">Report</h2>',
            'id'     => 'modal',
            'size'   => 'modal-sm',
            'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
    
    echo "<div id='modelContent'></div>";
    Modal::end();
    Modal::begin([
              'header' => '<h2 class="modal-title">New Address</h2>',
              'id'     => 'address-modal',
              'size'   => 'modal-md',
              'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
    ]);
     
    echo "<div id='modelContent'></div>";
    
  Modal::end() ?>

    <div id="userprofile" class="row">
      <div class="userprofile-header">
        <div class="userprofile-header-title"><?php echo Html::encode($this->title)?></div>
      </div>
      <div class="userprofile-detail">
        <div class="col-sm-3 userprofile-left">
          <div class="userprofile-avatar">
              <?php $picpath = is_null($user->userdetails->User_PicPath) ? Url::to('@web/imageLocation/Default.png'): $user->userdetails->User_PicPath ?>
              <?php echo Html::img($picpath,['class'=>"userprofile-image"])?>
              <?= Html::a('Edit', ['/user/userdetails'], ['class'=>'btn btn-default userprofile-editbutton']) ?>
          </div>
        </div>
        <div class="col-sm-9 userprofile-right">
          <h4>Detail</h4>
          <div class="userprofile-input">
              <div class="row">
                <div class="col-xs-2 userprofile-label">user name</div>
                <div class="col-xs-6 userprofile-text"><?php echo $user->username?></div>
              </div>
              <div class="row">
                <div class="col-xs-2 userprofile-label">full name</div>

                <div class="col-xs-6 userprofile-text"><?php echo $user->userdetails->fullname ?></div>
              </div>
              <div class="row">
                <div class="col-xs-2 userprofile-label">contact</div>
                <div class="col-xs-6 userprofile-text"><?php echo empty($user->userdetails->User_ContactNo) ? "not set" :$user->userdetails->User_ContactNo ?></div>
              </div>
          </div>

          <div class="userprofile-address">
            <?php echo Html::a("Add New Address",['/user/newaddress'],['class' => 'btn btn-success pull-right','data-toggle'=>'modal','data-target'=>'#address-modal'])?>
        
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
                      <td><?php echo $address->level == 1 ? '<span class="btn btn-danger btn-block">Primary</span>': Html::a('Mark as Primary',['/user/primary-address','id' => $address->id ])?></td>
                      <td><?php echo $address->FullAddress?></td>
                      <td>
                        <div class="row">
                          <div class="col-xs-6">
                            <?php echo Html::a("<span class='glyphicon glyphicon-pencil userprofile-pencil'></span>",['/user/edit-address','id'=> $address->id] )?>
                          </div>
                          <div class="col-xs-6">
                            <?php echo Html::a("<span class='glyphicon glyphicon-trash userprofile-trash'></span>",['/user/delete-address','id'=> $address->id] )?>    
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
        
        