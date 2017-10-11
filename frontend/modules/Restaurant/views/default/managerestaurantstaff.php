<?php
use yii\helpers\Html;
use common\models\user\Userdetails;
$this->title = "Manage Staff";
?>
<head>
<title>LOL</title>
</head>
<body>
<br>
<br>
<br>
<div class = "container">
<?php $picpath = $id['Restaurant_RestaurantPicPath'];

        if (is_null($id['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
         echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>"; ?>
    <h1><center><?php echo $id['Restaurant_Name']."'s Staff"; ?></h1>
<br>
<table class = "table table-add-staff" style="width:70%; margin:auto; border:0px solid;">
    <tr>
        <td><?php echo "<center>".Html::a('Add Owner', ['add-staff', 'rid'=>$id['Restaurant_ID'], 'num'=>"1"], ['class'=>'btn btn-primary'])."</td>"; ?>
        <td><?php echo "<center>".Html::a('Add Manager', ['add-staff', 'rid'=>$id['Restaurant_ID'], 'num'=>"2"], ['class'=>'btn btn-primary'])."</td>"; ?>
        <td><?php echo "<center>".Html::a('Add Operator', ['add-staff', 'rid'=>$id['Restaurant_ID'], 'num'=>"3"], ['class'=>'btn btn-primary'])."</td>"; ?>
    </tr>
    </table>
    <br>
    <br>
    <div>
    <table class = "table table-restaurant-staff" style="width:70%; margin:auto;">
    <tr>
    <th colspan = 2><center> Username </th>
    <th><center> Position </th>
    <th><center> Date Time Added </th>
    <th><center> Delete </th>
    </tr>
        <?php foreach ($rstaff as $data)
        {
            $pic = Userdetails::find()->where('User_Username = :uname',[':uname'=>$data['User_Username']])->one();
            if(is_null($pic['User_PicPath']))
            {
                $picpath = "DefaultPic.png";
            }
            else
            {
                $picpath = $pic['User_PicPath'];
            }

            if ($data['User_Username'] == $id['Restaurant_Manager'])
            {
                echo "<tr>";
                echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
                echo "<td><center>".$data['User_Username']."</td>";
                echo "<td><center>".$data['RmanagerLevel_Level']."</td>";
                $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
                $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
                echo "<td><center>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
                echo "</tr>";
            }
            else
            {
            echo "<tr>";
            echo "<td>".Html::img($picpath, ['class' => 'img-responsive', 'style'=>'height:40px; width:55px; margin:auto;'])."</td>";
            echo "<td><center>".$data['User_Username']."</td>";
            echo "<td><center>".$data['RmanagerLevel_Level']."</td>";
            $dt = new DateTime('@'.$data['Rmanager_DateTimeAdded']);
            $dt->setTimeZone(new DateTimeZone('Asia/Kuala_Lumpur'));
            echo "<td><center>".$dt->format('d-m-Y H:i:s')."</td>"; //Returns IST
            echo "<td><center>".Html::a('Delete', ['delete-restaurant-staff', 'rid'=>$data['Restaurant_ID'], 'uname'=>$data['User_Username']], ['class'=>'btn btn-primary'])."</td>";
            echo "</tr>";
            }
        } ?>
    </table>
    <br>

    </div>
</div>
</body>