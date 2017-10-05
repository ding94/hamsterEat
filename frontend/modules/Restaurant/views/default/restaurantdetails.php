<?php
use yii\helpers\Html;
$this->title = $id['Restaurant_Name'];
?>
<body>
<div class = "container">
    <?php $picpath = $id['Restaurant_RestaurantPicPath'];

        if (is_null($id['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
         echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'img-responsive', 'style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>"; ?>
    <h1><center><?php echo $id['Restaurant_Name']; ?></h1>
    <?php if (!Yii::$app->user->isGuest)
    {
        if ($id['Restaurant_Manager'] == Yii::$app->user->identity->username)
        {
            echo "<table class= table table-user-information style= width:100%; margin:auto;>";
            echo "<tr>";
                echo "<td><center>".Html::a('Edit Details', ['edit-restaurant-details', 'rid'=>$id['Restaurant_ID'], 'restArea'=>$id['Restaurant_AreaGroup'], 'areachosen'=>$id['Restaurant_Area'], 'postcodechosen'=>$id['Restaurant_Postcode']], ['class'=>'btn btn-primary'])."</td>";
                echo "<br> <br>";
                echo "<td><center>".Html::a('Manage Staffs', ['manage-restaurant-staff', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
                echo "<br> <br>";
                echo "<td><center>".Html::a('Restaurants Orders', ['/order/restaurant-orders', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
                echo "<br> <br>";
                echo "<td><center>".Html::a('Manage Menu', ['/food/menu', 'rid'=>$id['Restaurant_ID']], ['class'=>'btn btn-primary'])."</td>";
            echo "</tr>";
            echo "</table>";
        }
    }
    ?>
    <hr>
    <br>

    <h2><center>Menu</h2>
    <div class = "foodItems">
        <?php
            {
                $id = isset($_GET['foodid']) ? $_GET['foodid'] : '';

                foreach($rowfood as $data) :
                echo "<a href="?> <?php echo yii\helpers\Url::to(['food-details','fid'=>$data['Food_ID']]); ?> <?php echo ">";
                echo "<table class = 'table table-food-details'>";
                echo "<br>";
                echo "<br>";

                echo "<tr>";
                $picpath = $data['PicPath'];

                if (is_null($data['PicPath']))
                {
                    $picpath = "DefaultRestaurant.jpg";
                }

                echo '<th rowspan = "4">' ?> <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'pull-left img-responsive','style'=>'height:200px; width:300px; margin:auto;']) ?> <?php echo "</th>";
                echo "<td> Food Name: </td>";
                echo '<td>'.$data['Name'].'</td>';
                echo "</tr>";
                // echo "<tr>";
                // echo "<td> Food Type: </td>";
                // echo '<td>'.$data['Food_Type'].'</td>';
                // echo "</tr>";
                echo "<tr>";
                echo "<td> Food Price (RM): </td>";
                echo '<td>'.$data['Price'].'</td>';
                echo "</tr>";
                echo "<tr>";
                echo "<td> Food Tags: </td>";
                foreach($data['foodType']as $type) :
                    echo "<td>".$type['Type_Desc']."</td>";
                endforeach;
                echo "</tr>";
                echo "<tr>";
                echo "<td> Food Desc: </td>";
                echo '<td>'.$data['Description'].'</td>';
                echo "</tr>";
                echo "</table>";
                echo "<br>";
                echo "<br>";
                echo "</a>";
            ?>
            <?php endforeach;
            }
        ?>
    </div>
</div>
</body>