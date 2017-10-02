<?php
use yii\helpers\Html;
$this->title = "Available Restaurants";
?>

<div class="container" id="index">
    <h1>Showing Available Restaurants In Your Area</h1>

    <?php
    {
        
    foreach($restaurant as $data) :
    echo "<div class='gallery_product col-sm-4 col-xs-12'>";
    echo "<a href="?> <?php echo yii\helpers\Url::to(['restaurant-details','rid'=>$data['Restaurant_ID']]); ?> <?php echo ">";
    
      $picpath = $data['Restaurant_RestaurantPicPath'];

        if (is_null($data['Restaurant_RestaurantPicPath'])){
            $picpath = "DefaultRestaurant.jpg";
        }
        echo '<th rowspan = "5">' ?> <?php echo Html::img('@web/imageLocation/'.$picpath, ['class' => 'pull-left img-responsive','style'=>'height:250px; width:350px; margin:auto;']) ?> <?php echo "</th>";
    echo "<table class = 'table table-restaurant-details'>";  
    echo "<tbody>";

    echo "<tr>";
      
        echo "<td> Name: </td>";
        echo '<td> '.$data['Restaurant_Name'].'</td>';
    echo "</tr>";
    echo "<tr>";
        echo "<td> Rating: </td>";
        echo '<td>'.$data['Restaurant_Rating'].'</td>';
    echo "</tr>";
    echo "<tr>";
        echo "<td> Tags: </td>"; 
        $tags=explode(",",$data['Restaurant_Tag']);
        echo '<td> [' .$tags[0].'] &nbsp; &nbsp; &nbsp; ['.$tags[1].'] &nbsp; &nbsp; &nbsp; ['.$tags[2]. '] </td>';
    echo "</tr>";
    echo "<tr>";
    if ($data['Restaurant_Pricing'] == 1)
    {
        echo "<td> Food Pricing: </td>";
        echo "<td> $ </td>";
        echo "</tr>";
    }
    else if ($data['Restaurant_Pricing'] == 2)
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
        echo '<td>'.$data['Restaurant_UnitNo'].', '.$data['Restaurant_Street'].', '.$data['Restaurant_Area'].', '.$data['Restaurant_Postcode'].'.</td>';
    echo "</tr>";
    echo "</table>";
    echo "</tbody>";
    echo "</a>";
     echo "</div>";
    ?>
    <?php endforeach;
   
    }
    ?>
    </div>

</div>
