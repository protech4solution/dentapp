<?php
  require_once("../admin/includes/connection.php");

  if(!empty($_GET['q'])) {
    $coun_id = $_GET["q"];
    $query ="select *
             from observations 
             where activity_id = '$coun_id'
             order by observation asc";
    $results = $conn->query($query);
  ?>
  <table class="table table-responsive table-borderless">
  <?php
    while ($row = $results->fetch_assoc()){
  ?>
    <tr>
      <td><input type="radio" id="observation_id" name="observtion_id" value="<?=$row['id'];?>"></td>
      <td><?=$row['observation'];?></td>
    </tr>
  <?php
    }
  }
?>
