<?php
/*
  $Id: product_reviews_write.php,v 1.55 2003/06/20 14:25:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $reviewid=$_REQUEST['review'];
  if(!isset($_REQUEST['review']) && is_null($_REQUEST['review'])){
    redir301();
    }

  $review_query = tep_db_query("select rd.use, rd.reviews_text, rd.keywords, m.manufacturers_name, pd.products_head_desc_tag, p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id JOIN manufacturers m on m.manufacturers_id=p.manufacturers_id JOIN reviews r on r.reviews_id='" . (int)$reviewid ."' JOIN reviews_description rd on rd.reviews_id=r.reviews_id where p.products_id = r.products_id and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($review_query)) {
    redir301();
  } else {
    $product_info = tep_db_fetch_array($review_query);
  }

  $url=urlencode('http://www.seacoastvitamins.com/product_info.php?products_id=' . $product_info['products_id']);
  $title=urlencode($product_info['use']);
  $desc=urlencode($product_info['reviews_text']);
  $keywords=urlencode($product_info['keywords']);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Write Review for <?php echo $product_info['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="/stylesheet.css">

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
    <td width="100%" valign="top"><div id="content">
		
      <?php echo $products_name; ?>
            <h1>Your Review Has Been Submitted!</h1>
            <p>
            Thank you for writing a review at Seacoast Vitamins! <b>To say thanks, please take 10% off your next order.</b>
            </p>
            <h3>Coupon Code: <?php echo $reviewid; ?>REVIEW10<br/>Receive 10% Off</h3>
                        <p style="clear:both;"><a href="/product_info.php?products_id=<?php echo $product_info['products_id']; ?>">Purchase more <?php echo $product_info['products_name'] ?></a></p>
            <b>Would you like to submit your review to any of these other Seacoast Vitamins partners?</b>

            <p>
                <ul class="BookmarkList">
                
                <li class="Bookmarkli">
                <a id="Socializerblogmemes" href="http://www.blogmemes.net/post.php?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;content=<?php echo $desc; ?>" target="_blank">
                <img src="/images/socializer/blogmemes.png" alt="delicious/" width="16" height="16" />&nbsp;Blogmemes</a>
                </li>
                
                <li class="Bookmarkli">
                <a id="Socializerdelicious" href="http://del.icio.us/post?v=2&amp;url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;notes=<?php echo $desc; ?>&tags;tags=<?php echo $keywords; ?>" target="_blank">
                <img src="/images/socializer/delicious.png" alt="delicious/" width="16" height="16" />&nbsp;del.icio.us</a>
                </li>
                                
                <li class="Bookmarkli">
                <a id="Socializerfurl" href="http://www.furl.net/storeIt.jsp?t=Socializer&amp;u=<?php echo $url; ?>" target="_blank">
                <img src="/images/socializer/furl.png" alt="Furl" width="16" height="16" />&nbsp;Furl</a></li>
                
                <li class="Bookmarkli">
                <a id="Socializernetscape" href="http://www.netscape.com/submit/?U=<?php echo $url; ?>&amp;T=<?php echo $title; ?>&amp;storyTags=<?php echo $keywords; ?>&amp;storyText=<?php echo $desc; ?>" target="_blank">
                <img src="/images/socializer/netscape.gif" alt="Netscape" width="16" height="16" />&nbsp;Netscape</a></li>
                
                <li class="Bookmarkli">
                <a id="Socializernewsvineplugim" href="http://www.plugim.com/submit?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;tags=<?php echo $keywords; ?>&amp;bodyText=<?php echo $desc; ?>&amp;select-i=<?php echo '4'; ?>" target="_blank">
                <img src="/images/socializer/plugim.png" alt="PlugIM" width="16" height="16" />&nbsp;PlugIM</a></li>
                
                <li class="Bookmarkli"><a id="Socializerreddit" href="http://reddit.com/submit?url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank">
                <img src="/images/socializer/reddit.png" alt="reddit" width="16" height="16" />&nbsp;reddit</a></li>
                
                <li class="Bookmarkli"><a id="Socializerslashdot" href="http://slashdot.org/bookmark.pl?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;tags=<?php echo $keywords; ?>" target="_blank">
                <img src="/images/socializer/slashdot.png" alt="Slashdot" width="16" height="16" />&nbsp;Slashdot</a></li>
                
                <li class="Bookmarkli"><a id="Socializersquidoo" href="http://www.squidoo.com/lensmaster/bookmark?<?php echo $url; ?>" target="_blank">
                <img src="/images/socializer/squidoo.gif" alt="Squidoo" width="16" height="16"/>&nbsp;Squidoo</a></li>
                
                <li class="Bookmarkli"><a id="Socializerstumbleupon"href="http://www.stumbleupon.com/submit?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>&amp;tagnames=<?php echo $keywords; ?>&amp;newcomment=<?php echo $desc; ?>" target="_blank">
                <img src="/images/socializer/stumbleit.gif" alt="Stumbleupon" width="16" height="16">&nbsp;StumbleUpon</a></li>
                
                <li class="Bookmarkli"><a id="Socializertechnorati" href="http://technorati.com/faves/?add=<?php echo $url; ?>&amp;tag=<?php echo $keywords; ?>" target="_blank">
                <img src="/images/socializer/technorati.gif" alt="Technorati" width="16" height="16" />&nbsp;Technorati</a></li>
                
                <li class="Bookmarkli">
                <a id="Socializermyweb2" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?php echo $url; ?>&amp;t=<?php echo $title; ?>" target="_blank">
                <img src="/images/socializer/yahoo.gif" alt="Yahoo My Web" width="16" height="16" />&nbsp;Yahoo My Web</a></li></ul><h2 class="GroupLabel">
            </p>
            <p style="clear:both;"></p>
            <br/>&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>
            <h3>Related Categories</h3><?php include(DIR_WS_MODULES . 'products_categories.php');?><?php related_product_categories($product_info['products_id']);?>
                          
            
       
</div>
</td>
		
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
