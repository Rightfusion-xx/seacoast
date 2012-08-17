<?php







  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");







  if ($number_of_rows = tep_db_num_rows($manufacturers_query)) {







?>



<tr>







  <td>







    <?php







    require(DIR_WS_INCLUDES . '/boxes/boxTop.php');







    echo BOX_HEADING_SPONSORED;







	require(DIR_WS_INCLUDES . '/boxes/boxMiddle.php');



echo 
'<p><img src="images/naturalfactslogo1.bmp" width="94" height="58"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1316">Aller 
    7 Formula</a></li>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1907">Quercetin</a></li>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=446">Lung, 
    Bronchial &amp; Sinus Health </a></li>
</ul>
<p><img src="images/naturesway.gif" width="100" height="60"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=450">HAS 
    Original Blend </a></li>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=174">Alpha 
    SH</a></li>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=173">Allergiemittel 
    (Alleraide)</a></li>
</ul>
<p><img src="images/newchapter.gif" width="157" height="30"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=2207">Chinese 
    Skullcap</a></li>
</ul>
<p><img src="images/nllogo.gif" width="150" height="100"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1697">Bromelain 
    Sinus Ease</a></li>
</ul>
<p><img src="images/solaraylogo.gif" width="150" height="50"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1985">QBC 
    Plex </a></li>
</ul>
<p><img src="images/we_mn_logo_01.gif" width="170" height="41"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=2060">Wobenzym 
    N Seven</a></li>
</ul>
<p><img src="images/LiddellLogo.gif" width="150" height="79"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1652">Allergy 
    Spray</a></li>
</ul>
<p><img src="images/natrabio.jpg" width="145" height="69"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=186">Cold 
    &amp; Sinus Nasal Spray</a></li>
</ul>
<p><img src="images/Boiron.png" width="104" height="48"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1839">Chestol 
    Honey + Oscillo 3 Dose</a></li>
</ul>
<p><img src="images/seacoast.gif" width="120" height="40"></p>
<ul>
  <li><a href="http://www.seacoastvitamins.com/product_info.php?products_id=937">Bromelain 
    500mg</a></li>
</ul>
<p><img src="images/Woodlandlogo.gif" width="158" height="57"></p>
<ul>
  <li>Milk Thistle Book</li>
</ul>';

    require(DIR_WS_INCLUDES . '/boxes/boxBottom.php');

?>







  </td>







</tr>
<?php







  }







?>

