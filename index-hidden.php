<?php

$linkcachetime=60*10;

  require('includes/application_top.php');
  
  $cache=new megacache(600); //10 minute cache
  
  if(!$cache->doCache('homepage'))
  {
  include_once('includes/functions/render_products.php');






  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>
<title>Vitamins Supplements & Minerals Online Discount Store | SEACOAST</title>
 <meta name="Description" content="Supplements & vitamins, minerals & herbs online store. Best product quality & discounts in the industry. Buy organic & natural health vits at SEACOAST Today!" />
 <meta name="Keywords" content="vitamin, supplement, vits, minerals, herbs, buy vitamins online, discount, health" /> 
 <meta name="robots" content="noodp" /> 
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <meta property="fb:admins" content="652610239" />

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<div class="container">
<div class="row">
<div class="span12">

                  <?php require(DIR_WS_INCLUDES . 'whyshop.php'); ?>


                  <?php require(DIR_WS_INCLUDES . 'front_ads.php'); ?>


           
   <h2>Popular Health Hubs</h2>
   
   <?php
    $hub_query =tep_db_query('select * from wp_posts p join wp_postmeta pm on pm.post_id=id where pm.meta_key="hub" order by post_modified_gmt desc');
    
    while($hub=tep_db_fetch_array($hub_query))
    {
        echo '<p><a href="/hub.php?tag='.$hub['meta_value'].'">'.$hub['post_title'].'</a> '.$hub['post_excerpt'].'';     
    }
    
    
    
    
    
?>

    
    
    </p>
    
    <h2>Recent Changes and Additions</h2>  
    <p><a href="/recent_changes.php">Recently updated and new products</a></p>
</div>  

</div>
</div>

<?php 
  $cache->addCache('homepage');
  } //end cache

require(DIR_WS_INCLUDES . 'footer.php'); ?>

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

