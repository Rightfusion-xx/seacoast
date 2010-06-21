<?php
  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {
?>
<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header">
    Friends of Seacoast

  </div>
  <a href="/brand.php">Our Partners</a>
  <?php
  }
?>
</div>