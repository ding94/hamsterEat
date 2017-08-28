<div class="container">
    <h1>Available Restaurants In Your Area</h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        <h2>Viewing restaurants under group : <?php echo $groupArea; ?></h2>
    </p>
    <div class = "table table-restaurant-details">

        <?php foreach($restaurant as $data) :?>
        <table>
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
        <?php endforeach;?>
    </div>

</div>
