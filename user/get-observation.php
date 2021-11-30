<?php
  require_once("../admin/includes/connection.php");

  if(!empty($_GET['country_id'])) {
    $coun_id = $_GET["country_id"];
    $query ="select *
             from observations 
             where activity_id = '$coun_id'";
    $results = $conn->query($query);
    ?>
<!--    <option value="">Select observation</option>-->
    <?php
    foreach($results as $state) {
      ?>
      <option value="<?php echo $state["id"]; ?>" style="width: 80%; word-break: break-all;"><?php echo $state["observation"]; ?></option>
      <?php
    }
  }
?>
