<?php


  require('includes/application_top.php');
  include_once('includes/functions/render_products.php');






  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

?>
<!doctype html>

<html <?php echo HTML_PARAMS; ?>>

<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
<title>Recently Updated Supplements</title>
 <meta name="Description" content="Vitamins, Herb, and Nutritional Supplements that have been updated within the last 7 days." />
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<div id="content">

                       <h1>Recently Updated Supplements</h1>
                       <?php require(DIR_WS_MODULES.'new_products.php')    ;?>
    
    
    </p>
</div>  

<?php 


require(DIR_WS_INCLUDES . 'footer.php'); ?>

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

