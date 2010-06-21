<?php
/*
$Id: footer.php,v 1.26 2003/02/10 22:30:54 hpdl Exp $
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2003 osCommerce
Released under the GNU General Public License
*/
require(DIR_WS_INCLUDES . 'counter.php');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="1">
  <tr>
    <?php echo '<TD COLSPAN="2" BGCOLOR="#FFCC99" CLASS="headernavigation" ALIGN="center"><IMG SRC="/images/spacer.gif" height="1" width="1"></TD></TR><TR>' .
'<td colspan="2" align="center" class="footer" BGCOLOR="#336699">&copy; <?php echo date("Y");?> Seacoast Natural Health Yoga</td></tr>';
//echo '<tr><td class="footer" align="right" background="' . DIR_WS_IMAGES . 'slice_sets/' . INFOBOX_FOOTER_SLICE_SET . '/top_background.jpg" height="' . INFOBOX_FOOTER_HEIGHT . '">&nbsp;&nbsp; Office Hours: Mon - Fri, 9am - 6pm Eastern Time &nbsp;&nbsp;</td>';
?>
  </tr>
</table>
<?php
// if ($banner = tep_banner_exists('dynamic', '468x50')) {
?>

<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
  _uacct = "UA-207538-1";
  urchinTracker();
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
// }

if(!isset($_COOKIE["divpop"]) &&  !tep_session_is_registered('customer_id') && (strpos($_SERVER['REQUEST_URI'],'index.php')>0 || strpos($_SERVER['REQUEST_URI'],'health_library.php')>0))
{
  //setcookie("divpop", "1", time()+604800);
  //require($_SERVER['DOCUMENT_ROOT'].'/includes/vert/div_pop_prevention.htm');
}

if((strpos($_SERVER['REQUEST_URI'],'product_info.php')>0)) {
  require($_SERVER['DOCUMENT_ROOT'].'/includes/vert/div_pop_onsale.htm');
}?>
