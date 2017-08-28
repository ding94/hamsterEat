<div class="container">
    <h1>Available Restaurants In Your Area</h1>

    <p>
        <h2>Viewing restaurants under group : <?php echo $groupArea; ?></h2>
    </p>

        <?php foreach($restaurant as $data) :?>
        <table class = "table table-restaurant-details">
        <br>
        <br>

        <tr>
            <th rowspan="4"> RESTPIC </th>
            <td> Restaurant ID: </td>
            <td> <?php echo $data['Restaurant_ID'] ?></td>
        </tr>
        <tr>
            <td> Restaurant Name: </td>
            <td> <?php echo $data['Restaurant_Name'] ?></td>
        </tr>
        <tr>
            <td> Restaurant Owner: </td>
            <td> <?php echo $data['Restaurant_Manager']; ?></td>
        </tr>
        <tr>
            <td> Restaurant Address: </td>
            <td> <?php echo $data['Restaurant_UnitNo'] . ", " . $data['Restaurant_Street'] . ", " . $data['Restaurant_Area'] . ", " . $data['Restaurant_Postcode']."."; ?></td>
        </tr>
        </table>
        <br>
        <br>
        <?php endforeach;?>
    </div>

</div>
