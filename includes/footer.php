<?php // inject header to appropriate place
  $start=ob_get_length();

?>


  <div id="main_content" style="margin-top:<?php if($_SESSION['cm_is_member']){ ?>13em;<?php }else{?>11em;<?php }?>">
  
  
  
      <?php  
    if ($cart->count_contents() > 0 && $_SERVER['HTTPS']=='off' && !$hide_cart) {
    ?>
    <p style="text-align:center;">
    	<div>
    	<table cellpadding="0" cellspacing="0" style="border:solid 1px #578dc7;margin-left:35px;">
    		<tr>
    			<td style="background: top left repeat-x url(/images/bar_blue.gif) #eee;padding:5px;" nowrap>
    				<h3 style="display:inline;font-size: 13px;color: #fff;">Shopping Cart</h3>
    			</td>
    			<td style="padding:5px;" nowrap>
    				<b><a href="/shopping_cart.php" style="color:#ff9900;">View All Items</a></b>
  	            &nbsp;&nbsp;
  	             <?php echo $cart->count_contents()?> item<?php if($cart->count_contents()>1) echo 's';?>: $<?php echo number_format($cart->show_total(),2);?>
  	            <b>(<span style="background:yellow;">Savings of $<?php echo number_format($cart->show_savings(),2); ?></span>)</b>
  	           
  	       		&nbsp;&nbsp;&nbsp;
  	            <b><a href="/checkout_shipping.php" style="color:#ff9900">>> Checkout >> Now >></a></b>
    			</td>
    		</tr>
    	</table>
      

        
      </div>
  
  </p>    
    <?php 
      }
  ?>
  
  
  
  
  
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

</div>

<div id="header" >
  <table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:100%" width="100%">
    <tr>
      <td nowrap width="210">
        <div id="header_logo" style="margin-left:5px;margin-bottom:1em;">
          <?php 
          if((int)$_REQUEST['products_id'])
          {
            echo '<img src="/images/seacoast_logo.png" border="0" alt="Vitamins from Seacoast Vitamins for Nutritional Supplements Online. Source of vitamins, minerals, herbs, and all your nutritional supplement needs." title=" Vitamins from Seacoast Vitamins for Nutritional Supplements Online. Source of vitamins, minerals, herbs, and all your nutritional supplement needs. " width="179" height="60">';
            
          }
          else
          {
            echo '<a href="/"><img src="/images/seacoast_logo.png" border="0" alt="Vitamins from Seacoast Vitamins for Nutritional Supplements Online. Source of vitamins, minerals, herbs, and all your nutritional supplement needs." title=" Vitamins from Seacoast Vitamins for Nutritional Supplements Online. Source of vitamins, minerals, herbs, and all your nutritional supplement needs. " width="179" height="60"></a>';
          }
          ?>
        </div>
      </td>
      <td >
        <?php if($_SERVER['HTTPS']=='off' && !$hide_cart)
                  {?>
        <div style="padding-left:15px;float:left;background-color:#ffffff;">
        <?php //if(strlen($_COOKIE['affiliate'])>0) echo '<b>In affiliation with <a href="'.$_COOKIE['affiliate'].'">'.$_COOKIE['affiliate'].'</a>.</b>';?>
        <?php if( (time()<strtotime('2008/10/10')||1==1) ){
          $latesttweet=tep_db_fetch_array(tep_db_query('select tweet_message from tweets order by tweet_datetime desc limit 0,1'));?>
          <div style="float:left;margin-left:20px;" onclick="window.location.href='http://twitter.com/SeacoastVits';" onmouseover="this.style.cursor='hand';">
            <img src="/images/green_light.gif" style="float:left;margin-top:10px;margin-right:10px;" border=0 />
            <br/>
            <span style="text-weight:bold;text-decoration:underline;color:blue;font-size:12pt;"><b>@SeacoastVits on Twitter</b></span><br/>
            <i><?php echo '"', @substr($latesttweet['tweet_message'],0,@strpos($latesttweet['tweet_message'],' ',60)),'<br/>',@substr($latesttweet['tweet_message'],@strpos($latesttweet['tweet_message'],' ',60-1)),'"';?></i>

          </div>
        
        <?php
        
        }else{/*?>

        <div style="text-align:center;padding:10px;float:left;">
          <!--<A href="http://www.aquasanaaffiliates.com/b.asp?id=3711" rel="nofollow" target="_blank">
            <img src="http://www.aquasanaaffiliates.com/showban.asp?id=3711&img=banner1.jpg" border="0">
              </a>-->
            </div>
        <?php */}} ?>
        
      </td>
      <td nowrap width="300">
        <div id="header_utilities">
                   <span id="account_utils" style="display:inline;text-align:right;float:right; margin-right:10px; margin-top:10px;white-space:nowrap;">
              <span style="text-align:right">

                <?php if (tep_session_is_registered('customer_id')) { ?>
                <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>" class="headerNavigation"> 
                <?php echo HEADER_TITLE_LOGOFF; ?>
                </a> &nbsp;|&nbsp; 
                <?php } ?>
                <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>" class="headerNavigation">
                <?php echo tep_session_is_registered('customer_id') ? 'My Account' : 'Log In To Your Account'; ?>
                
                </a><?php if($cart->count_contents()>0){?> &nbsp;|&nbsp; 
                <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="headerNavigation">
                Shopping Cart
                </a> &nbsp;|&nbsp; <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>" class="headerNavigation"> 
                <?php echo HEADER_TITLE_CHECKOUT; ?>
                </a> &nbsp;&nbsp; <?php } ?></span>

            </span>
            <br/> <br/>
          
          

         <p style="font-size:10pt;font-weight:bold;color:#CC6600;text-align:right;margin-right:20px;">U.S. 800.555.6792<br/>
          Int'l (1+) 702.508.9054</p>
        </div>
      </td>
    </tr>
    <tr>
      <td nowrap colspan="3">
        <div style="color:#333367;font-weight:bolder;clear:both;margin:0px;padding:7px;white-space:nowrap;background-repeat:repeat;background-image:url('/images/head-bg-fade.gif');border-bottom:1px solid #333367">
          <form name="quick_find" action="/topic.php" method="get">
            <input type="text" name="health" size="10" maxlength="250" style="width: 200px" value="<?php echo $_REQUEST['health']?>"/>&nbsp;<input class="formbutton" type="submit" value="Search" alt="Search Seacoast" title=" Search Seacoast "/>
            &nbsp;&nbsp;
            <?php 
            if((int)$_REQUEST['products_id'])
          {?>
            <a href="/index.php?products_id=<?php echo (int)$_REQUEST['products_id']?>">Health Guides</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="/ailments.php?products_id=<?php echo (int)$_REQUEST['products_id']?>">Ailments &amp; Diseases</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="/natural_uses.php?products_id=<?php echo (int)$_REQUEST['products_id']?>">Symptoms</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            
            <?php
              $mflink=link_exists('/index.php?manufacturers_id='.(int)$product_info['manufacturers_id'],$page_links);
              if(!strlen($mflink))
              {
               
                $mflink='/index.php?manufacturers_id='.(int)$product_info['manufacturers_id'];
              }

            ?>
            <a href="<?php echo $mflink;?>">Brands</a>
          <?php }else{?>
            <a href="/health-guides/">Health Guides</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="/ailments-diseases/">Ailments &amp; Diseases</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="/symptoms/">Symptoms</a>&nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="/brand.php">Brands</a>
          <?php }?>

            <?php if(!$_SESSION['cm_is_member']) {?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/community/">Join Vitamins-Direct</a>   <?php } ?>
          </form>
          <?php if($_SESSION['cm_is_member']){ ?><span style="color:#CC6600">
          	<hr class="sectiondivider" style="margin-top:10px;margin-bottom:10px;"/>
          	Member since <?php echo $_SESSION['cm_member_since']; ?>.
          	Total Savings of $<?php echo number_format($_SESSION['cm_savings'],2); ?></span>
          
          <?php } ?>

        </div>        
      </td>
    </tr>
    
  </table>
  
 <!--
  <div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix" style="margin-top:2em; margin-left:10%;margin-right:10%;margin-bottom:2em;0px;display:none;" id="freeTrialInfo">
  Not a member? <span style="text-decoration:line-through">Only $50 per year.</span> FREE for 14-days!
  <br/>
  <ul>
  	<li>Exclusive pricing</li>
  	<li>Extra 15%-25% off every order</li>
  	<li>Share your membership with 8 friends</li>
  	
  </ul>
  
  </div>    -->
  
  

</div>
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
</div>




<?php if(1==2){?>

<link type="text/css" href="/jquery/css/ui-lightness/jquery-ui-1.7.1.custom.css" rel="Stylesheet" />	
<script type="text/javascript" src="/jquery/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/jquery/js/jquery-ui-1.7.1.custom.min.js"></script>

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
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-207538-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>


<?php if($_SERVER['HTTPS'] == 'off'){ ?>
<!-- Start Quantcast tag -->
<script type="text/javascript" src="http://edge.quantserve.com/quant.js"></script>
<script type="text/javascript">
  _qacct="p-8bGAfSCJCy0J2";quantserve();
</script>
<noscript>
  <img src="http://pixel.quantserve.com/pixel/p-8bGAfSCJCy0J2.gif" style="display: none" height="1" width="1" alt="Quantcast"/>
</noscript>
<?php } ?>
<!-- End Quantcast tag -->


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


