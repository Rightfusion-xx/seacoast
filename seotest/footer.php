<?php // inject header to appropriate place
  $start=ob_get_length();

?>


  <?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="headerError">
      <td class="headerError">
        <?php echo htmlspecialchars(urldecode($HTTP_GET_VARS['error_message'])); ?>
      </td>
    </tr>
  </table>
  <?php
  }
  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
  ?>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="headerInfo">
      <td class="headerInfo">
        <?php echo htmlspecialchars($HTTP_GET_VARS['info_message']); ?>
      </td>
    </tr>
  </table>
  <?php
  }
  ?>

<div id="header" >

    <div class="container">
  <div  class="row">
      <div class="span4">
          <?php
            echo '<a href="'.HTTP_SERVER.'/"><img src="/images/seacoast_logo.png" border="0"
              alt="" title="Vitamins, Exclusive Discounts, Direct to You. " width="179" height="60">
              </a>';
          ?>
      </div>
      <div class="span4">
          <div style="text-align:center;">
              <form name="quick_find" action="/topic.php" method="get" style="display:inline;">
                  <input type="text" name="health" size="10" maxlength="250" style="width: 200px" value="<?php echo $_REQUEST['health']?>"/>&nbsp;
                  <input class="primary" type="submit" value="Search" alt="Search Seacoast" title=" Search Seacoast "/>
              </form>
          </div>
          <div style="text-align:center;">
            <a href="/brands">Our Brands</a>
            <?php if(!$_SESSION['cm_is_member']) {?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/community/">Join Vitamins-Direct</a>   <?php } ?>
          </div>
      </div>
      <div class="span4" style="text-align:right">

              <?php if (tep_session_is_registered('customer_id')) { ?>
                  <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation">
                      <?php echo HEADER_TITLE_LOGOFF; ?>
                  </a> &nbsp;|&nbsp;
                  <?php } ?>
              <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation">
                  <?php echo tep_session_is_registered('customer_id') ? 'My Account' : 'Log In To Your Account'; ?>

              </a>
              <?php if($cart->count_contents()>0){?> &nbsp;|&nbsp;
                  <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation" rel="nofollow">
                      Shopping Cart
                  </a>
                  <?php } ?>


              <p style="font-size:10pt;color:#CC6600;font-weight:bold;text-align:right;">U.S. 800.555.6792<br/>
                  Int'l (1+) 702.508.9054</p>

      </div>




 </div>
 </div>
    <hr style="width:100%; height:1px; background-color:#333367;" />
  <?php
  if($do_admin && $authenticated)
  {
      ?>
      <div id="admin" class="green box">
      User: <?php echo substr($_SERVER['PHP_AUTH_USER'],0,strpos($_SERVER['PHP_AUTH_USER'],'@'));?>
      </div>

      <?php
  }
        ?>


</div>

<?php if ($cart->count_contents() > 0 && $_SERVER['HTTPS']=='off' && !$hide_cart): ?>

<div style="padding-top:17px; margin-bottom:40px;" class="container alert">
    <div class="row show-grid">
        <div style="padding-top:4px;" class="span2">
            <h5 style="display:inline;padding-left:5px;">Shopping Cart</h5>
        </div>
    <div class="span2">
        <input class="btn" type="button" value="View All Items" onClick="document.location='/shopping_cart.php';">
    </div>
    <div class="span5" style="padding-top:4px;">
        <h5 style="display:inline;padding-left:5px;">
            <?php echo $cart->count_contents()?> item<?php if($cart->count_contents()>1) echo 's';?>: $<?php echo number_format($cart->show_total(),2);?>
            &nbsp;&nbsp;
            (<span>Savings of $<?php echo number_format($cart->show_savings(),2); ?></span>)
        </h5>
    </div>
    <div class="span2" style="white-space: nowrap;">
            <input class="btn" type="button" value="Checkout Now" onClick="document.location='/checkout_shipping.php';">
            <input class="btn" type="button" value="Publish Shopping Cart" onClick="window.open('/publish_cart.php');">
    </div>

</div>
</div>
<?php endif; ?>

<?php
   $header_inject=substr(ob_get_contents(),$start);
   $buffer=substr(ob_get_clean(),0,$start-1);
   ob_start();
   echo str_replace('$HEADER$',$header_inject, $buffer);


  ?>


  <?php if($cart->count_contents() < 1 && $_SERVER['HTTPS']=='off'){?>
<div id="chitika" style="padding:30px 0 30px 0;text-align:center;display:block;">
 <!-- Chitika -->
<script type="text/javascript"><!--
ch_client = "NealBozeman";
ch_type = "mpu";
ch_width = 550;
ch_height = 250;
ch_non_contextual = 4;
ch_vertical ="premium";
ch_sid = "Topic Pages";
var ch_queries = new Array( );
var ch_selected=Math.floor((Math.random()*ch_queries.length));
if ( ch_selected < ch_queries.length ) {
ch_query = ch_queries[ch_selected];
}
//--></script>
<script  src="http://scripts.chitika.net/eminimalls/amm.js" type="text/javascript">
</script>

</div>

<?php }
 ?>


<div id="footer">

  <?php if($_SERVER['HTTPS'] == 'off'){ ?>
  <div style="text-align:center;width:100%;">


</div>
  <?php }else{ ?>
  <div style="text-align:center;width:100%;">
  <a href="/terms-sale.php" target="_blank">Terms of Sale</a>&nbsp;&nbsp;|&nbsp;&nbsp;
  <a href="/terms-site.php" target="_blank">Website Terms</a>&nbsp;&nbsp;|&nbsp;&nbsp;
  <a href="/terms-privacy.php" target="_blank">Privacy Policy</a>&nbsp;&nbsp;|&nbsp;&nbsp;
  <a href="/community/terms.php" target="_blank">Vitamins-Direct Terms</a>
  </div>
  <?php } ?>

<div id="footer-signoff">&nbsp;&nbsp; Order Online 24 Hours a Day, 7 Days a Week &nbsp;&nbsp;&nbsp;
Call Toll-Free 800-555-6792 &nbsp;&nbsp;&nbsp;
Hours of Operation: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;<br>&copy; 1996-<?php echo date("Y")?> Seacoast Natural Health

 </div>
<div class="smallText">
<p>
      <?php if(strlen($product_info['products_name'])>0){
echo 'Statements & claims about '.$product_info['products_name'].' & natural alternative remedies have not been evaluated by the FDA. Information herein is not intended to diagnose, cure or prevent disease, and is not provided by '.$product_info['manufacturers_name'].'.' .
' Before starting any exercise, diet or supplementation program using '.$product_info['products_name'].', consult with a qualified health care professional, especially if you are taking prescription medications & drugs. Our cheap, discounted prices on '.$product_info['products_name'].', vitamin supplements & nutritional food supplements are updated daily and change without notice.';
}
else{
echo '1. Seacoast Health Encyclopedia statements or claims have not been evaluated by the FDA.';}

?>
</p>

<div style="margin:1em;text-align:center;">


</div>
</div>


<?php
if($authenticated && $do_admin)
{
     ?>

    <link type="text/css" href="/jquery/css/ui-lightness/jquery-ui-1.7.1.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/jquery/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/jquery/js/jquery-ui-1.7.1.custom.min.js"></script>

      <?php
}
?>
<?php if(1==2){?>
<?php
if(!$cart->in_cart(CM_FTPID) && !$cart->in_cart(CM_PID) && !$_SESSION['cm_is_member']){  ?>
<script type="text/javascript">
	$.ui.dialog.defaults.bgiframe = true;
	$.ui.dialog.defaults.resizable = false;
	$.ui.dialog.defaults.height = 400;
	$.ui.dialog.defaults.width = 600;
	$.ui.dialog.defaults.autoOpen = false;

	$(function() {
		$('#dialog').dialog();
		$('#dialog').dialog('option', 'show', 'bounce');
		$('#dialog').dialog('open');

		$('#dialog').bind('dialogclose', function(event, ui) {

			  showHeaderFreeTrial();
			});



	});


	function showHeaderFreeTrial()
	{

		document.getElementById('main_content').style.marginTop='19em';
		document.getElementById('freeTrialInfo').style.display='block';
		$("#freeTrialInfo").effect("bounce",null,500);

	}






	</script>



<div id="dialog" title="Try us FREE">
	<h2 style="color:#000000;font-weight:bold;">
		Special Offer
	</h2>

	<b>Seacoast Vitamins Direct</b> is <b>FREE</b> for the first 14-Days. There are no obligations.

	<ul>
  	<li>Exclusive pricing</li>
  	<li>Extra 15%-25% off every order</li>
  	<li>Share your membership with 8 friends</li>
	<ul>
	<b><a href="/get_started.php">Get started now...</a></b>


	<b>Already a Member? Log In</b><br/>
	email<br/>
	password<br/>


	</div>


<?php } }?>

<?php
/*
if(!isset($_COOKIE["divpop"]) &&  !tep_session_is_registered('customer_id') && (strpos($_SERVER['REQUEST_URI'],'index.php')>0 || strpos($_SERVER['REQUEST_URI'],'health_library.php')>0))
{
  //setcookie("divpop", "1", time()+604800);
  //require($_SERVER['DOCUMENT_ROOT'].'/includes/vert/div_pop_prevention.htm');
}
*/
/*
if((strpos($_SERVER['REQUEST_URI'],'product_info.php')>0)) {
  require($_SERVER['DOCUMENT_ROOT'].'/includes/vert/div_pop_onsale.htm');
}
*/

//Check for referrer. If there, log referrer
/*
if(isset($_SERVER['HTTP_REFERER']) && !strpos(HTTP_SERVER, $_SERVER['HTTP_REFERER']))
{
  $refDomain=parse_section($_SERVER['HTTP_REFERER'],'.','/');


  if($aff=tep_db_fetch_array(tep_db_query('select * from postaff.wd_g_users where weburl like "%'.$refDomain.'%" order by dateinserted limit 0,1')))
  {
  ?>

<script type="text/javascript" src="/includes/javascript/ajaxlib.js"></script>
<script type="text/javascript">
<!--
    function postaffhandler()
    {
      if(this.readyState==4)
        {
          MessageBox(this.status);
        }


    }

    var client=createRequestObj();
    client.onreadystatechange=postaffhandler;
    client.open("GET","/affiliates/scripts/t.php?a_aid=<?php echo $aff['userid'] ?>&a_bid=d5b174f9");


    if(document.referrer != '')
        document.write('<script src="http://www.seacoastvitamins.com/affiliates/scripts/sr.php?ref='+escape(document.referrer)+'"></script>');
//-->
</script>

  <?php
  setcookie('affiliate',$refDomain,time()+94608000);
  }

} */
?>


<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-207538-1']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_trackPageLoadTime']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();



</script>




<?php
  if((strpos($_SERVER['PHP_SELF'],'healthnotes.php')>0 || strpos($_SERVER['PHP_SELF'],'article_info.php')>0) && 1==2)
  {
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
<?php
  }
?>

 </div>

 <?php

  // check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
  if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
  $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
  }
  }
  // check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
  if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
  $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
  }
  }
  // check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
  if (STORE_SESSIONS == '') {
  if (!is_dir(tep_session_save_path())) {
  $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
  } elseif (!is_writeable(tep_session_save_path())) {
  $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
  }
  }
  }
  // check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
  if (ini_get('session.auto_start') == '1') {
  $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
  }
  }
  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
  if (!is_dir(DIR_FS_DOWNLOAD)) {
  $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
  }
  }
  if ($messageStack->size('header') > 0) {
  echo $messageStack->output('header');
  }
  ?>


