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
<title>Vitamins, Minerals, Supplements | Seacoast Vitamins</title>
 <meta name="Description" content="Seacoast Vitamins - the vitamins store that provides quality vitamins, minerals, herbs, and all your nutritional supplement needs at everday low prices. Free health information." />
 <meta name="Keywords" content="vitamins, herbs, herbal extracts, alternative medicines, homeopathic, health, natural, nutritional supplements, healthy living, ADHD, cancer, prostate, diabetes, arthritis" /> 
 <meta name="robots" content="noodp" /> 
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <meta property="fb:admins" content="652610239" />

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<div id="content">

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

               
               
   <h2>Catalog of Nutritional Supplements</h2>
    <p>
    <?php
    
    foreach(automated_catalog::all() as $catalog)
    {
        
        echo '<a href="/catalog.php?page='.$catalog->pagenum.'">'.$catalog->linktext.'</a> &nbsp; ';
    }    

?>
    
    
    </p>
    
    <h2>Recent Changes and Additions</h2>  
    <p><a href="/recent_changes.php">Recently updated and new products</a></p>
</div>  

<?php 
  $cache->addCache('homepage');
  } //end cache

require(DIR_WS_INCLUDES . 'footer.php'); ?>

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

