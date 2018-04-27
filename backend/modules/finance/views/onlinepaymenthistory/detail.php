<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;


foreach ($model as $key => $value) {

}

?>

  <table class="table table-hover">
      <tr>
        <th>Bill ID</th>
         <td><?php echo $value['id']; ?></td>
      </tr>
      <tr>
        <th>Collection ID</th>
        <td><?php echo $model['data']['collection_id']; ?></td>
      </tr>
       <tr>
        <th>Name</th>
        <td><?php echo $model['data']['name']; ?></td>
      </tr>
       <tr>
        <th>Mobile</th>
        <td><?php echo $model['data']['mobile']; ?></td>
      </tr>
       <tr>
        <th>Email</th>
        <td><?php echo $model['data']['email']; ?></td>
      </tr>
      <tr> 
        <th>Status</th>
         <td><?php echo $model['data']['state']; ?></td>
      </tr>
       </tr> 
      <tr> 
        <th>Amount</th>
        <td><?php echo $model['data']['amount']; ?></td>
      </tr> 
       <tr> 
        <th>Pay Amount</th>
        <td><?php echo $model['data']['paid_amount']; ?></td>
      </tr> 
       <tr> 
        <th>Due At</th>
        <td><?php echo $model['data']['due_at']; ?></td>
      </tr> 
       <tr> 
        <th>Paid At</th>
        <td><?php echo $value['paid_at']; ?></td>
      </tr> 
       <tr> 
        <th>Description</th>
        <td><?php echo $value['description']; ?></td>
      </tr> 
       <tr> 
        <th>Link</th>
        <td><?php echo HTML::a($value['url'],$value['url'], ['target' => '_blank']); ?></td>
      </tr> 

      
  </table>