<?php


?>
<td valign="top"><div id="rightnav" style="margin-left:5%;margin-right:10%;margin-top:10%">

  <div id="yoga_menu" class="nav_box">
    <script type="text/javascript">
      <!--
google_ad_client = "pub-6691107876972130";
google_ad_width = 200;
google_ad_height = 90;
google_ad_format = "200x90_0ads_al_s";
//2007-09-12: Yoga
google_ad_channel = "5112816388";
//-->
    </script>
    <script type="text/javascript"
      src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    <?php 
      if(strlen($nearby_city_links)>1)
      {?>
        <div class="nav_header" style="margin-top:10px;">Nearby Cities</div>
        
          <?php echo $nearby_city_links;  ?>
        
      
      <?php
      }
    
    ?>
    <?php 
      if(strlen($yf_state)>1)
      {?>
    <div class="nav_header" style="margin-top:10px;">More <?php echo ucwords($city_info['statename']);?> Yoga Studios
  </div>

    <?php echo $yf_state;  ?>


    <?php
      }
    
    ?>
    
    <div class="nav_header" style="margin-top:10px;">Yoga Finder</div>
    <ul>
      <li><a href="/yoga/yogafinder.php">Find Yoga Studios</a></li>
    </ul>
    
    <div class="nav_header" style="margin-top:10px;">Yoga at Home</div>
    <ul>
      <li><a href="/yoga/myo.php"><b>Yoga Videos Online</b></a></li>
    </ul>
   
    <div class="nav_header" style="margin-top:10px;">Yoga Topics</div>
    <ul>
      <li><a href="/yoga/method.php">Yoga Methods</a></li>
      <li><a href="/yoga/">Pose, Postures & Asanas</a></li>
      <li><a href="/yoga/benefits.php">Yoga by Benefits</a></li>
      <li><a href="/yoga/routine.php">Yoga Routines</a></li>
      <li><a href="/yoga/skill.php">Yoga Difficulty Levels</a></li>
    </ul>
    
   <?php

?>
    <!-- Kontera ContentLink(TM);-->
    <script type='text/javascript'>
      var dc_AdLinkColor = 'orange' ;
      var dc_UnitID = 14 ;
      var dc_PublisherID = 12757;
      var dc_adprod = 'ADL' ;
    </script>
    <script type='text/javascript' 
    src='http://kona.kontera.com/javascript/lib/KonaLibInline.js'>
    </script>
    <!-- Kontera ContentLink(TM) -->

  </td></tr></table>
