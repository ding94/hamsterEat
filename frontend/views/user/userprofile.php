<?php
use yii\helpers\Html;
 $this->title = 'My Profile';
?>

<div class="container">
	<div class="tab-content col-md-6 col-md-offset-3" id="userprofile">
		<h1>User Profile</h1>

             <tr>
            
            <td> 
            <img class="img-rounded img-responsive"  src=<?php 
            $picpath = $user->userdetails->User_PicPath;
            if(is_null($user->userdetails->User_PicPath))
            { 
              echo $picpath= "/hamstereat/frontend/web/imageLocation/Default2.png";
           
              }
            else
            {
            echo $user->userdetails->User_PicPath;
            } ?>>
            </td>
            </tr>
			  </div>
			  <div class="col-lg-6 col-lg-offset-1">
			<table class="table table-user-information" id="userdetails">
            <tr>
            <td>User Name:</td>
            <td> <?php echo $user->username;?></td>
            </tr>
              <tr>
              <td>Email:</td>
              <td><?php echo $user->email;?></td>
              </tr>
             <tr> 
             <td>Full Name:</td>
             <td><?php echo $user->userdetails->User_FirstName;?> <?php echo $user->userdetails->User_LastName;?></td>
             </tr>
               <tr>
               <td>Contact Number:</td>
               <td> <?php echo $user->userdetails->User_ContactNo;?></td>
               </tr>
                  <tr>
                  <td>Address 1:</td>
               <td> <?php echo $user->useraddress->User_HouseNo1.','.$user->useraddress->User_Street1.','.$user->useraddress->User_Area1.','.$user->useraddress->User_Postcode1	?></td>
               </tr>
               <tr>
                  <td>Address 2:</td>
               <td> <?php echo $user->useraddress->User_HouseNo2,$user->useraddress->User_Street2,$user->useraddress->User_Area2,$user->useraddress->User_Postcode2	?></td>
               </tr>
               <tr>
                  <td>Address 3:</td>
               <td> <?php echo $user->useraddress->User_HouseNo3,$user->useraddress->User_Street3,$user->useraddress->User_Area3,$user->useraddress->User_Postcode3	?></td>
               </tr>
                  <tr>
                 <td> <?= Html::a('Edit', ['/user/userdetails'], ['class'=>'btn btn-primary']) ?> </td>
                    </tr>
                
                   
            </table>
            </div>
			</div>
            </div>
        