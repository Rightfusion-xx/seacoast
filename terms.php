<?php


  require('includes/application_top.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>



  <title>Seacoast Vitamins Terms & Conditions</title>
<meta name="Description" content="Seacoast Vitamins complete terms and conditions, terms of use and terms of sale." />
 <meta name="Keywords" content="terms, conditions, use, sale" />


<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">



<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="content">
      <h1>Seacoast Vitamins Terms & Conditions</h1>
      <p>
          In addition to excellent customer service and Direct to Member pricing, Seacoast is
          pleased to provide our complete Terms & Conditions of our website, SeacoastVitamins.com, as well
          as complete Privacy Policy and Terms and Conditions of Sale.
      </p>
      <ol>
          <li><a href="/terms-privacy.php">Privacy Policy</a></li>
           <li><a href="/terms-site.php">Website Terms of Use</a></li>
           <li><a href="/terms-sale.php">Terms and Conditions of Sale</a></li>
           <li><a href="/community/terms.php">Seacoast Vitamins-Direct Pricing Membership & Free Trial Terms and Conditions</a></li>

      </ol>
</div>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
