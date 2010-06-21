<?php


  require('includes/application_top.php');
//fast easy checkout start
tep_session_unregister('payment');
//fast easy checkout end 
// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }



// load all enabled payment modules

  

  
  if($_REQUEST['action']=='update'){
  	
  	if($_REQUEST['cm_renew']!='1')
  	{
  		//disable auto-renew and billing;
  		tep_db_query('update customers_info set cm_renew=0 where customers_info_id='.(int)$customer_id);
  		$updated=true;  		
  		refresh_user_info();
  	}else{
  		
  		//update billing and auto renew
  		//validate card info
  		include(DIR_WS_CLASSES . 'cc_validation.php');
  		$cc=new cc_validation();
  		$result=$cc->validate($_REQUEST['cc_number'], $_REQUEST['cc_expires_month'], $_REQUEST['cc_expires_year'], $_REQUEST['cvvnumber']);
  		switch((int)$result)
  		{
  		case -1:
          $error = sprintf(TEXT_CCVAL_ERROR_UNKNOWN_CARD, substr($cc->cc_number, 0, 4));
          break;
        case -2:
        case -3:
        case -4:
          $error = TEXT_CCVAL_ERROR_INVALID_DATE;
          break;
        case false:
          $error = TEXT_CCVAL_ERROR_INVALID_NUMBER;
          break;
        default:
        	//success, update card
	  		$updated=true; 
	  		tep_db_query('update customers_info set cm_renew=1 where customers_info_id='.(int)$customer_id);
	  		tep_db_query('update customers set cc_owner="'.tep_db_input($_REQUEST['cc_owner']).'",
	  					cc_number="'.tep_db_input($cc->cc_number).'", cvv_number="'.tep_db_input($_REQUEST['cvvnumber']).'",
	  					cc_expires="'.tep_db_input($cc->cc_expiry_month.substr($cc->cc_expiry_year,2,2)).'", cc_type="'.tep_db_input($cc->cc_type).'"
	  					where customers_id='.(int)$customer_id);
	  		refresh_user_info();
	  		tep_redirect('account.php');
	  		break;
  		}
  	}

 }
     require(DIR_WS_CLASSES . 'payment.php');

  $payment_modules = new payment;  

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo 'Vitamins-Direct Membership - Seacoast Vitamins'; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//begin cvv contribution
function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=450,screenX=150,screenY=150,top=150,left=150')
}
//end cvv contribution
//--></script>
<?php echo $payment_modules->javascript_validation(); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

 

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

    <div id="content">
    <?php if($updated) echo '<b>Your settings have been updated</b>' ?>
	<?php echo tep_draw_form('checkout_payment', tep_href_link('account_cm.php', '', 'SSL'), 'post', 'onsubmit="return check_form();"'); ?>
	<input type="hidden" name="action" value="update">
	<h1>Seacoast Vitamins-Direct Membership</h1>
                            
                    <?php
  if (isset($error)) {
?>
                    
                    <p class="quote">
                    	<?php echo $error; ?>
                    </p>
                                    
                                  
                    <?php
  unset($error);}
  
?>
<h2>Renew Membership</h2>
<p>
<input name="cm_renew" value="1" type="checkbox" <?php if($_SESSION['cm_renew'] || $_REQUEST['cm_renew']=='1') echo 'checked'; ?>> Renew my subscription on <?php echo $_SESSION['cm_expiration'] ?>
</p>

<div id="payment_info">
<h2>Update Payment Information</h2>
                   <p>
                     <?php	require('includes/fec/payment_box.php');?>
                   </p>
                   <p>
                        <table border="0" cellspacing="1" cellpadding="2" class="infoBox">
                          <tr class="infoBoxContents"> 
                            <td> 
                              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td width="10"> 
                                    <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                  </td>
                                  
                                  <td class="main" align="right"> 
                                    <?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
                                    <BR>
                                    <FONT SIZE="-2">(Information is encrypted 
                                    for your privacy and security).</FONT></td>
                                  <td width="10"> 
                                    <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                                  </td>
                                </tr>
                              </table>
                             </td>
                            </tr>
                            
                           </table>
                           
                         </p>
                </form></div>
                </div><br/>
           
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'secure_footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
