<?php



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

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<div id="content">

                  <?php require(DIR_WS_INCLUDES . 'whyshop.php'); ?>


                  <?php require(DIR_WS_INCLUDES . 'front_ads.php'); ?>


                  <?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?>

              

               </div>


<?php 
  $cache->addCache('homepage');
  } //end cache

require(DIR_WS_INCLUDES . 'footer.php'); ?>

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

