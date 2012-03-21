<?php







  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");







  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {







?>



<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header">Our Favorite</div>







  <?php











echo 
'<b><font face="Arial, Helvetica, sans-serif" size="2"><a href="http://www.seacoastvitamins.com/product_info.php?products_id=692">IBX - Nautral Gas and Cramp Relief</a></font></b></p>
<a href="http://www.seacoastvitamins.com/product_info.php?products_id=692"><img src="images/ibximage.jpg" border="0"alt="IBx Soothing Bowel Formula"></a></p>

120 Capsules<br><p>

</font></b></font>
Promotes Healthy<br>Bowel Function!<p>
Relieves gas<br>and cramping!<p>
Reduces abdominal<br>discomfort!<p>

<p><a href="http://www.seacoastvitamins.com/product_info.php?products_id=692"><b><font face="Arial, Helvetica, sans-serif" size="2">Try 

IBx Soothing Bowel Formula!<br></a> <font color="#FF0000">Our Price: $17.95</font></font></b><br>';









?>





</div>
<?php







  }







?>

