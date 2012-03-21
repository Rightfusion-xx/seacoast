<?php
/*
  $Id: product_reviews.php,v 1.50 2003/06/09 23:03:55 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/classes/review_handler.php');

  $product_info_query = tep_db_query("select psu.product_upc, psu.product_sku, pd.products_head_desc_tag, pd.products_head_keywords_tag, m.manufacturers_name, m.manufacturers_id, p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id left outer JOIN products_sku_upc psu ON psu.product_ids = p.products_id JOIN manufacturers m ON m.manufacturers_id = p.manufacturers_id where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and  pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText"><a href="index.php?manufacturers_id='.$product_info['manufacturers_id'].'">' . $product_info['manufacturers_name'] . '</a></span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $product_info['manufacturers_name'],' ',$product_info['products_name'] ?> Customer Review</title>
<meta name="Description" content="Customer reviews for <?php echo $product_info['manufacturers_name'],' ',$product_info['products_name'] ?> "/>
<meta name="Keywords" content="<?php echo $product_info['products_head_keywords_tag'] ?>"/>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
		
		<table border="0" width="100%" cellspacing="0" cellpadding="12">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><h1>Customer Reviews for <?php echo $products_name; ?></h1>
		<span style="font-size:10pt;">
			<?php
			 //$product_info['product_sku']="";
			 if (strlen($product_info['product_sku'])>0){
					echo 'SKU: '.$product_info['product_sku']; }
			?> 
			  <br>   		
				<?php
			 //$product_info['product_upc']="";
			 if (strlen($product_info['product_upc'])>0 ){
					echo 'UPC: '.$product_info['product_upc']; }
			?> 
			</span>    			
			   </td>
            <td class="pageHeading" align="right" valign="top"><?php //echo $products_price; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td align="center" class="smallText" width="113"> 
                  <?php
  if (tep_not_null($product_info['products_image'])) {
?>
                  <script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
<?php } ?>
                  <noscript> 
                  <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
                  </noscript></td>
                <td align="center" class="smallText" width="195"> <noscript></noscript> 
                  <form name="buy_now" action="/product_reviews.php?action=buy_now&products_id=<?php echo $product_info['products_id']?>" method="POST">
                    <input type="image" src="/includes/languages/english/images/buttons/button_in_cart.gif" border="0" alt="Add to Cart" title=" Add to Cart " align=absmiddle></form>
                </td>
              </tr>
            </table>
            
          <p>Below are positive & negative customer reviews of <a href="/product_info.php?products_id=<?php echo $product_info['products_id'] ?>"><?php echo $product_info['products_name'] ?></a> including effectiveness, potency and bioavailability. <?php echo $product_info['products_name'] ?> is
		  manufactured by <?php echo $product_info['manufacturers_name'] ?> under specific guidelines. Help the community determine the good and bad by adding your own review to <?php echo $product_info['products_name'] ?> by clicking on "write review."</p>
		  <p><?php echo $product_info['products_head_desc_tag'] ?></p></td>
      </tr>
      <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td valign="top">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <?php
	
	$query =  "select r.*, rd.* from ".TABLE_REVIEWS. " r, ".TABLE_REVIEWS_DESCRIPTION." rd "; 
	$query .= "where r.products_id=".(int)$product_info['products_id']." ";
	$query .= "and r.reviews_id = rd.reviews_id and "; 
	$query .= "rd.languages_id = '1' order by date_added desc";
	 
    $reviews_query_raw = $query;//"select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where review_parent_id is null and r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc";
    $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>
                    <tr> 
                      <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr> 
                            <td class="smallText">
                              <?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>
                            </td>
                            <td align="right" class="smallText">
                              <?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr> 
                      <td>
                        
                      </td>
                    </tr>
                    <?php
    }

	$reviews_query = tep_db_query($reviews_split->sql_query);
 	
	$parentResultset = tep_db_query($query);
	$childResultset = tep_db_query($query);
	
	$reviewHandler = new review_Handler($parentResultset);
	  
	  while ($reviews = tep_db_fetch_array($parentResultset)) {
		if ($reviews['review_parent_id'] == NULL) // is parent
	  	{ 	
  		?>
            <tr> 
              <td>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php
                    $reviewHandler->writeToPage($reviews); // print parent
                    $reviewHandler->printChildren($childResultset,$reviews['reviews_id']); // print children                	
                  ?>
                </table>
              </td>
            </tr>
                    <tr> 
                      <td>
                        
                      </td>
                    </tr>
                    <?php
        }
		$childReviewPadding = 0;
    }
?>
                    <?php
  } else {
?>
                    <tr> 
                      <td>
                        <?php new infoBox(array(array('text' => TEXT_NO_REVIEWS))); ?>
                      </td>
                    </tr>
                    <tr> 
                      <td>
                        
                      </td>
                    </tr>
                    <?php
  }

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
                    <tr> 
                      <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                          <tr> 
                            <td class="smallText">
                              <?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?>
                            </td>
                            <td align="right" class="smallText">
                              <?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr> 
                      <td>
                        
                      </td>
                    </tr>
                    <?php
  }
?>
                    <tr> 
                      <td>
                        <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                          <tr class="infoBoxContents"> 
                            <td>
                              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td width="10">
                                    
                                  </td>
                                  <td class="main">
                                    <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params()) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>
                                  </td>
                                  <td class="main" align="right">
                                    <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?>
                                  </td>
                                  <td width="10">
                                   
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" align="right" valign="top">&nbsp; 
                </td>
            </table>
          </td>
      </tr>
    </table></td>
		
<!-- body_text_eof //-->
</TR></TABLE>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>