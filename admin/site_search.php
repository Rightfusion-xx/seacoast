<?php
/*
  $Id: orders.php,v 1.112 2003/06/29 22:50:52 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>


<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo 'Seacoast Admin : Site Search'; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">



</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" <?php if($order->info['paid']=='1' && isset($_REQUEST['transid'])){?> onload="" <?php }?> >
<div id="all_info">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top" class="hide-when-print"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
    
    <div id="cse" style="width: 100%;">Loading</div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript">
  google.load('search', '1', {language : 'en', style : google.loader.themes.GREENSKY});
  google.setOnLoadCallback(function() {
    var customSearchControl = new google.search.CustomSearchControl('000660513327178783876:iyzm6gd4gv0');
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
    customSearchControl.draw('cse');
  }, true);
</script>

<style type="text/css">
  .gsc-control-cse {
    font-family: "Trebuchet MS", sans-serif;
    border-color: #E1F3DA;
    background-color: #E1F3DA;
  }
  input.gsc-input {
    border-color: #94CC7A;
  }
  input.gsc-search-button {
    border-color: #94CC7A;
    background-color: #AADA92;
  }
  .gsc-tabHeader.gsc-tabhInactive {
    border-color: #A9DA92;
    background-color: #FFFFFF;
  }
  .gsc-tabHeader.gsc-tabhActive {
    border-color: #A9DA92;
    background-color: #A9DA92;
  }
  .gsc-tabsArea {
    border-color: #A9DA92;
  }
  .gsc-webResult.gsc-result {
    border-color: #A9DA92;
    background-color: #FFFFFF;
  }
  .gsc-webResult.gsc-result:hover {
    border-color: #A9DA92;
    background-color: #FFFFFF;
  }
  .gs-webResult.gs-result a.gs-title:link,
  .gs-webResult.gs-result a.gs-title:link b {
    color: #0066CC;
  }
  .gs-webResult.gs-result a.gs-title:visited,
  .gs-webResult.gs-result a.gs-title:visited b {
    color: #0066CC;
  }
  .gs-webResult.gs-result a.gs-title:hover,
  .gs-webResult.gs-result a.gs-title:hover b {
    color: #0066CC;
  }
  .gs-webResult.gs-result a.gs-title:active,
  .gs-webResult.gs-result a.gs-title:active b {
    color: #0066CC;
  }
  .gsc-cursor-page {
    color: #0066CC;
  }
  a.gsc-trailing-more-results:link {
    color: #0066CC;
  }
  .gs-webResult.gs-result .gs-snippet {
    color: #454545;
  }
  .gs-webResult.gs-result .gs-visibleUrl {
    color: #815FA7;
  }
  .gs-webResult.gs-result .gs-visibleUrl-short {
    color: #815FA7;
  }
  .gsc-cursor-box {
    border-color: #A9DA92;
  }
  .gsc-results .gsc-cursor-page {
    border-color: #A9DA92;
    background-color: #FFFFFF;
  }
  .gsc-results .gsc-cursor-page.gsc-cursor-current-page {
    border-color: #A9DA92;
    background-color: #A9DA92;
  }
  .gs-promotion.gs-result {
    border-color: #94CC7A;
    background-color: #CBE8B4;
  }
  .gs-promotion.gs-result a.gs-title:link {
    color: #0066CC;
  }
  .gs-promotion.gs-result a.gs-title:visited {
    color: #0066CC;
  }
  .gs-promotion.gs-result a.gs-title:hover {
    color: #0066CC;
  }
  .gs-promotion.gs-result a.gs-title:active {
    color: #0066CC;
  }
  .gs-promotion.gs-result .gs-snippet {
    color: #454545;
  }
  .gs-promotion.gs-result .gs-visibleUrl,
  .gs-promotion.gs-result .gs-visibleUrl-short {
    color: #815FA7;
  }
</style>

      </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</div>
<?php if(is_numeric($oID) && $oID>0 && $order->info['paid']=='1'){echo '<div class="page-break"></div><div class="hide-till-print" style="width:100%">';require ('includes/invoice.php');echo '</div>';}?>

</body>
</html>


<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>


<?php

function CapturePayment($order)
{
/*
    $pfpXml='<?xml version="1.0" encoding="UTF-8"?><XMLPayRequest Timeout="30" version = "2.0" xmlns="http://www.paypal.com/XMLPay"> 
                <RequestData>
                    <Vendor>'.PFP_VENDOR.'</Vendor>
                    <Partner>'.PFP_PARTNER.'</Partner>';
                    
    $pfpXml.='<Transactions>
                <Transaction>
                    <Authorization>
                        <PayData>
                            <Invoice>
                                <TotalAmt>'.$orders['order_total'].'</TotalAmt>
                            </Invoice>
                            <Tender>
                                <Card>
                                    <CardType>'.$order->info['cc_type'].'</CardType>
                                    <CardNum>'.$order->info['cc_number'].'</CardNum>
                                    <ExpDate>20'.right($order->info['cc_expires']),2).left($order->info['cc_expires']),2)</ExpDate>
                                    <CVNum>'.$order->info['cvvnum']).'</CVNum>
                                    <NameOnCard>'.$order->info['cc_owner']).'<NameOnCard/>
                                </Card>
                            </Tender>
                        </PayData>
                    </Authorization>
                </Transaction>
                </Transactions>';          
    
    $pfpXml.='</RequestData>
                <RequestAuth>
                    <UserPass>
                        <User>'.PFP_USER.'</User>
                        <Password>'.PFP_PASSWORD.'</Password>
                    </UserPass>
                </RequestAuth>
            </XMLPayRequest>';
    
    $remote=curl_init();
    $headers = array(
            "Content-Length: ".strlen($pfpXml),
            "Content-Type: text/xml",
            "Host: ".PFP_HOST",
            "X-VPS-REQUEST-ID: ".time(),
            "X-VPS-CLIENTTIMEOUT: 45"); 
       
    curl_setopt($remote, CURLOPT_HTTPHEADER, $headers);    
    curl_setopt($remote, CURLOPT_URL,'https://'.PFP_HOST.'/transaction');
	curl_setopt($remote, CURLOPT_VERBOSE, 1);

	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($remote, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($remote, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($remote, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($remote, CURLOPT_POST, 1);
    curl_setopt($remote, CURLOPT_FORBID_REUSE, TRUE);


	curl_setopt($remote,CURLOPT_POSTFIELDS,$pfpXml);

	//getting response from server
	echo $pfpXml.'<br/>';
	$response = curl_exec($remote);
	
	echo $response;
	echo curl_getinfo($remote);
*/
}

?>