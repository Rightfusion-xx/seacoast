<?php



  require('../dev/includes/application_top.php');
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

<?php



// BOF: WebMakers.com Changed: Header Tag Controller v1.0



// Replaced by header_tags.php



if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {



  require(DIR_WS_INCLUDES . 'header_tags.php');



} else {



?>

<title>

<?php echo TITLE ?>

</title>

<?php



}





?>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">


<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" id="scv_header">

  <TR>

    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" id="scv_leftcol" rowspan="2">

	  <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="0" CELLPADDING="0">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

      </TABLE></TD><td valign="top" colspan="2" valign="top" width="100%"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr>

<!-- body_text //-->


    <td  valign="top"><div id="content">

                  <?php require(DIR_WS_INCLUDES . 'whyshop.php'); ?>


                  <?php require(DIR_WS_INCLUDES . 'front_ads.php'); ?>


                  <?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?>

                   <?php include(DIR_WS_MODULES . 'new_cheapest_products.php'); ?>
              

               </div></td>




<!-- body_text_eof //-->

   <TD VALIGN="top" id="scv_rightcol">

     <TABLE BORDER="0"  CELLSPACING="2" CELLPADDING="0">

<!-- right_navigation //-->

<?php
   if (isset($_REQUEST['cPath']) or  isset($_REQUEST['manufacturers_id'])) {

 require(DIR_WS_INCLUDES . 'column_right.php'); }?>

<!-- right_navigation_eof //-->

     </TABLE></TD></TR></TABLE>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

