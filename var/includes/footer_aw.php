<?php
require(DIR_WS_INCLUDES . 'counter.php');
?>

<br style="clear:both"/>
  <?php if($_SERVER['HTTPS'] == 'off'){ ?>
  
<div style="text-align:center">
<a href="/details.php">Ordering Details</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="/shipping.php">Shipping &amp; Returns</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="/privacy.php" rel="nofollow">Privacy Guarantee</a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="/contact_us.php">Contact Us</a><br/><br/>
  
</div>
  <?php } ?>

<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr>
    <?php echo '<TD COLSPAN="2" BGCOLOR="#FFCC99" CLASS="headernavigation" ALIGN="center"><IMG SRC="/images/spacer.gif" height="1" width="1"></TD></TR><TR>' .
'<td colspan="2" align="center" class="footer" BGCOLOR="#336699">&nbsp;&nbsp; Order Online 24 Hours a Day, 7 Days a Week &nbsp;&nbsp;&nbsp; Call Toll-Free 800-555-6792 &nbsp;&nbsp;&nbsp; Live Assistance: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;<br>&copy; 2008 Seacoast Natural Health</td></tr>';
//echo '<tr><td class="footer" align="right" background="' . DIR_WS_IMAGES . 'slice_sets/' . INFOBOX_FOOTER_SLICE_SET . '/top_background.jpg" height="' . INFOBOX_FOOTER_HEIGHT . '">&nbsp;&nbsp; Live Support: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;</td>';
?>
  </tr>
</table>
<?php
// if ($banner = tep_banner_exists('dynamic', '468x50')) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="12">
  <tr>
    <td class="smallText">
      <?php if(strlen($product_info['products_name'])>0){
echo 'Statements & claims about '.$product_info['products_name'].' & natural alternative remedies have not been evaluated by the FDA. Information herein is not intended to diagnose, cure or prevent disease. '.
'Prices change daily.';
}
else{
echo '1. Seacoast Health Encyclopedia statements or claims have not been evaluated by the FDA.';}

?>
    </td>
  </tr>
</table>

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
<?php

if(!isset($_COOKIE["divpop"]) &&  !tep_session_is_registered('customer_id') && (strpos($_SERVER['REQUEST_URI'],'index.php')>0 || strpos($_SERVER['REQUEST_URI'],'health_library.php')>0))
{
  //setcookie("divpop", "1", time()+604800);
  //require($_SERVER['DOCUMENT_ROOT'].'/includes/vert/div_pop_prevention.htm');
}

if((strpos($_SERVER['REQUEST_URI'],'product_info.php')>0)) {
  require($_SERVER['DOCUMENT_ROOT'].'/includes/vert/div_pop_onsale.htm');
}


//Check for referrer. If there, log referrer
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
  
}

?>
