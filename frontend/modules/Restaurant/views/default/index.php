<?php
use yii\helpers\Html;
?>

<div class="container">
    <h1>Showing Available Restaurants In Your Area</h1>

    <?php
    {
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    foreach($restaurant as $data) :
    echo "<a href="?> <?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$data['Restaurant_ID']]); ?> <?php echo ">";
    echo "<table class = 'table table-restaurant-details'>";
    echo "<br>";
    echo "<br>";

    echo "<tr>";
        $picpath = $data['Restaurant_RestaurantPicPath'];

        if (is_null($data['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
        echo '<th rowspan = "4">' ?> <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'pull-left img-responsive','style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>";
        echo "<td> Restaurant ID: </td>";
        echo '<td> '.$data['Restaurant_ID'].'</td>';
    echo "</tr>";
    echo "<tr>";
        echo "<td> Restaurant Name: </td>";
        echo '<td>'.$data['Restaurant_Name'].'</td>';
    echo "</tr>";
    echo "<tr>";
        echo "<td> Restaurant Owner: </td>";
        echo '<td>'.$data['Restaurant_Manager'].'</td>';
    echo "</tr>";
    echo "<tr>";
        echo "<td> Restaurant Address: </td>";
        echo '<td>'.$data['Restaurant_UnitNo'].', '.$data['Restaurant_Street'].', '.$data['Restaurant_Area'].', '.$data['Restaurant_Postcode'].'.</td>';
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
