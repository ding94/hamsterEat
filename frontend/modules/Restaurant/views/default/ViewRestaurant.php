   <?php
use yii\helpers\Html;
?>

<body>

<div class = "container" ><h1>Restaurant</h1>
<?php
{

   foreach($restaurant as $restaurant):
    echo "<a href="?> <?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$restaurant['Restaurant_ID']]); ?> <?php echo ">";
    echo "<table class = 'table table-restaurant-details'>";
    echo "<br>";
    echo "<br>";
       echo "<tr>";
        $picpath = $restaurant['Restaurant_RestaurantPicPath'];

        if (is_null($restaurant['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
        echo '<th rowspan = "5">' ?> <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'pull-left img-responsive','style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>";
        echo "<td> Name: </td>";
        echo '<td> '.$restaurant['Restaurant_Name'].'</td>';
    echo "</tr>";
    echo "<tr>";
        echo "<td> Rating: </td>";
        echo '<td>'.$restaurant['Restaurant_Rating'].'</td>';
    echo "</tr>";
    echo "<tr>";
        echo "<td> Tags: </td>"; 
        $tags=explode(",",$restaurant['Restaurant_Tag']);
        echo '<td> [' .$tags[0].'] &nbsp; &nbsp; &nbsp; ['.$tags[1].'] &nbsp; &nbsp; &nbsp; ['.$tags[2]. '] </td>';
    echo "</tr>";
    echo "<tr>";
    if ($restaurant['Restaurant_Pricing'] == 1)
    {
        echo "<td> Food Pricing: </td>";
        echo "<td> $ </td>";
        echo "</tr>";
    }
    else if ($restaurant['Restaurant_Pricing'] == 2)
    {
        echo "<td> Food Pricing: </td>";
        echo "<td> $ $ </td>";
        echo "</tr>";
    }
    else
    {
        echo "<td> Food Pricing: </td>";
        echo "<td> $ $ $ </td>";
        echo "</tr>";
    }
    echo "<tr>";
        echo "<td> Address: </td>";
        echo '<td>'.$restaurant['Restaurant_UnitNo'].', '.$restaurant['Restaurant_Street'].', '.$restaurant['Restaurant_Area'].', '.$restaurant['Restaurant_Postcode'].'.</td>';
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

       