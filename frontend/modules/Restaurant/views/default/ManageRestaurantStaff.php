<?php
use yii\helpers\Html;
?>
<head>
<title>LOL</title>
</head>
<body>
<div class = "container">
<?php $picpath = $id['Restaurant_RestaurantPicPath'];

        if (is_null($id['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
         echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>"; ?>
    <h1><center><?php echo $id['Restaurant_Name']."'s Staff"; ?></h1>
<br>    
    <div>
    <table class = "table table-restaurant-staff" style="width:70%; margin:auto;">
    <tr>
    <th><center> Username </th>
    <th><center> Position </th>
    <th><center> Date Time Added </th>
    <th><center> Delete </th>
    </tr>
        <?php foreach ($rstaff as $data)
        {
            echo "<tr>";
            echo "<td><center>".$data['User_Username']."</td>";
            echo "<td><center>".$data['RmanagerLevel_Level']."</td>";
            $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
            $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
            echo "<td><center>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
            echo "<td><center>".Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'btn btn-primary'])."</td>";
            echo "</tr>";
        } ?>
    </table>
    <br>
    <?php echo "<center>".Html::a('Add Staffs', ['manage-restaurant-staff', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary']); ?>
    </div>
</div>
</body>