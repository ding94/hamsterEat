<?php
use yii\helpers\Html;
?>

<div class="container">
	<div class="tab-content col-md-7 col-md-offset-1" id="userprofile">
		<table class="table table-user-information"><h1>User Profile</h1>

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
             <td><?php echo $userdetails->User_FirstName;?> <?php echo $userdetails->User_LastName;?></td>
             </tr>
               <tr>
               <td>Contact Number:</td>
               <td> <?php echo $userdetails->User_ContactNo;?></td>
               </tr>
                  <tr>
                  <td>Address 1:</td>
               <td> <?php echo $useraddress->User_HouseNo1,$useraddress->User_Street1,$useraddress->User_Area1,$useraddress->User_Postcode1	?></td>
               </tr>
                  <tr>
                 <td> <?= Html::a('Edit', ['/user/userdetails'], ['class'=>'btn btn-primary']) ?> </td>
                    </tr>
                
                   
            </table>
            </div>
            </div>
        