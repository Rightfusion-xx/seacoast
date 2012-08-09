<?php

  require('includes/application_top.php');



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
  <title>New Year's Rewards from Seacoast Vitamins</title>


<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta name="description" content="Join for free to Seacoast Vitamins for special discounts, instantly."/>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

 

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
	  <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
      </TABLE></TD>
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->


    <td width="100%" valign="top">

    <div id="content">
    <h1>New Year's Rewards from Seacoast Vitamins</h1>
    
            
            
            <?php 
if (!isset ($_REQUEST['email'])) {
?>

<?php 
}
else { 

$nlquery="insert into newsletter_emails(email) values('".tep_db_input($_REQUEST['email'])."')"; 
tep_db_query($nlquery);

?>

<p>Thank you for joining Seacoast Vitamins.</p>
<p style="color:red;font-size:14pt;">
  New Year's Rewards ends soon. Shop now.
  
</p>
<p>
  <h2>
    Shop <a href="/">Seacoast Vitamins</a>
  </h2>
</p>

<?php } ?>

<p>Through the end of December, 2007, Seacoast Vitamins is pleased to offer <b>New Year's Rewards</b> with $50 free in the month of January, 2008, when you order $99 or more of 
nutritional supplements now through December 31st. Receive a $50 Rewards Certificate valid for the month of January for products throughout the store. Hurry, this offer ends December 31st, 2007. Start your 
New Year's Resolutions now. See details below.</p>

<p><b>Details</b><ul>
<li>Simply order $99 or more at Seacoast Vitamins before the end of December 31st, 2007, and receive a <b>$50 Rewards Certificate</b> valid during the month of January, 2008. Previous orders
from the month of December, 2007, count towards Rewards Certificate.</li>
<li>Sorry - Shipping charges, Prostasol, and select items marked * (asterisk) not valid towards Rewards Certificate. Not valid with other offers.</li>
<li>$50 Rewards Certificate can be used on any products in the store, except Prostasol and select items marked * (asterisk). Not valid towards shipping.</li>
<li>1 (one) Rewards Certificate per household. Rewards Certificate expires January 31st, 2008. Not valid with other offers.</li>
<li>Hurry. This offer will not be around long.</li></ul>
  
</p>
<p style="color:red;font-size:14pt;">
  $50 Rewards Certificate for $99 in Qualifying Purchases Ends December 31st, 2007
</p>
  If you'd like to receive additional communications about special offers and healthy living, 
  join the Seacoast Vitamins newsletter. Our newsletter contains product information, special events, promotions
  and valuable discounts only available to newsletter subscribers. Your email 
  is 100% safe and secure, <b>never shared or sold.</b>
</p>
<form action="january_rewards.php" method="post">
  <table border="0" style="border:solid 1px #CCCCCC;" cellspacing="5" width="315">
    <tr>
      <td>
        <font face="Arial, Helvetica, sans-serif" size="2">
          <b>
            <font color="#FFFFFF">
              <font color="#000000">Email Address </font>
            </font>
          </b>
        </font>
      </td>
      <td>
        <input type="text" size="30" name="email">
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <input type="submit" value="Submit">
        </td>
    </tr>
  </table>
</form>
          </div></td>

<!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
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
