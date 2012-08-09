<?php
/*
 $Id: product_reviews_info.php,v 1.50 2003/06/20 14:25:58 hpdl Exp $

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2003 osCommerce

 Released under the GNU General Public License
 */

require ('includes/application_top.php');
require('includes/classes/review_handler.php');

if (isset($HTTP_GET_VARS['review']) && tep_not_null($HTTP_GET_VARS['review'])) {
	$review_check_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$HTTP_GET_VARS['review'] . "' and  r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "'");
	$review_check = tep_db_fetch_array($review_check_query);

	if ($review_check['total'] < 1) {
		tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('review'))));
	}
} else {
	tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('review'))));
}

tep_db_query("update " . TABLE_REVIEWS . " set reviews_read = reviews_read+1 where reviews_id = '" . (int)$HTTP_GET_VARS['review'] . "'");

$review_query = tep_db_query("select keywords, rd.use, m.manufacturers_id, manufacturers_name, customers_name, pd.products_head_keywords_tag, p.products_id, rd.reviews_text, r.reviews_rating, r.reviews_id, r.date_added, r.reviews_read, p.products_id, p.products_price, p.products_tax_class_id, p.products_image, p.products_model, pd.products_name from " . TABLE_REVIEWS . " r join  " . TABLE_REVIEWS_DESCRIPTION . " rd on r.reviews_id=rd.reviews_id join " . TABLE_PRODUCTS . " p on p.products_id=r.products_id join  " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id=p.products_id JOIN manufacturers m on m.manufacturers_id=p.manufacturers_id where r.reviews_id = '" . (int)$HTTP_GET_VARS['review'] . "' and rd.languages_id = '" . (int)$languages_id . "' and  p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "'");
$review = tep_db_fetch_array($review_query);

if ($new_price = tep_get_products_special_price($review['products_id'])) {
	$products_price = '<s>' . $currencies -> display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies -> display_price($new_price, tep_get_tax_rate($review['products_tax_class_id'])) . '</span>';
} else {
	$products_price = $currencies -> display_price($review['products_price'], tep_get_tax_rate($review['products_tax_class_id']));
}

if (tep_not_null($review['products_model'])) {
	$products_name = '<h1 style="display:inline;">' . tep_output_string_protected($review['customers_name']) . '\'s ' . $review['products_name'] . ' Review</h1><br/><span class="smallText">[<a href="/index.php?manufacturers_id=' . $review['manufacturers_id'] . '">' . $review['manufacturers_name'] . '</a>]</span>';
} else {
	$products_name = $review['products_name'];
}

require (DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_INFO);

$breadcrumb -> add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
?>
<!doctype html>
<html <?php echo HTML_PARAMS;?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
		<title><?php echo $review['use'],' ',$review['products_name']
			?></title>
		<meta name="Description" content="Review of <?php echo $review['products_name'] ?> by <?php echo $review['customers_name'] ?> "/>
		<meta name="Keywords" content="<?php echo $review['keywords'] ?>"/>
		<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
		<script language="javascript">
			<!--
			function popupWindow(url) {
				window.open(url, 'popupWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
			}

			//-->
		</script>
	
	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
		<!-- header //-->
		<?php
			require (DIR_WS_INCLUDES . 'header.php');
		?>
		<!-- header_eof //-->
		<!-- body //-->
		<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
			<TR>
				<TD WIDTH="<?php echo BOX_WIDTH;?>" VALIGN="top" rowspan="2">
				<TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH;?>" CELLSPACING="2" CELLPADDING="0">
					<!-- left_navigation //-->
					<?php
						require (DIR_WS_INCLUDES . 'column_left.php');
					?>
					<!-- left_navigation_eof //-->
				</TABLE></TD>
				<td valign="top" colspan="2" valign="top"><?php
					require (DIR_WS_INCLUDES . 'titlebar.php');
				?></td>
			</tr>
			<tr>
				<!-- body_text //-->
				<td width="100%" valign="top">
				<table border="0" width="100%" cellspacing="0" cellpadding="12">
					<tr>
						<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top"><?php echo $products_name;?></td>
							</tr>
						</table></td>
					</tr>
					<tr>
						<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td valign="top">
								<table border="0" width="100%" cellspacing="0" cellpadding="2">
									<tr>
										<td>
										<table border="0" width="100%" cellspacing="0" cellpadding="2">
											<tr>
												<td class="main"><a href="/product_info.php?products_id=<?php echo $review['products_id'];?>"><?php echo $review['use'];?></a>
												<br/>
												<?php echo '<b>' . 'Written by ' . tep_output_string_protected($review['customers_name']) . '</b>';?></td>
												<td class="smallText" align="right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($review['date_added']));?></td>
											</tr>
										</table></td>
									</tr>
									<tr>
										<td>
										<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
											<tr class="infoBoxContents">
												<td>
												<table border="0" width="100%" cellspacing="0" cellpadding="2">
													<tr>
														
														<td valign="top" class="main"><?php echo tep_break_string(nl2br(tep_output_string_protected($review['reviews_text'])), 60, '-<br>') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $review['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $review['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $review['reviews_rating'])) . '</i>';?></td>

													</tr>
												</table></td>
											</tr>
										</table></td>
									</tr>
<?php 
		
	$review_id = $HTTP_GET_VARS['review'];
	
	$parentQuery =  "select r.*, rd.* from ".TABLE_REVIEWS. " r, ".TABLE_REVIEWS_DESCRIPTION." rd "; 
	$parentQuery .= "where r.products_id=(select products_id from reviews where reviews_id=".$review_id.") ";
	$parentQuery .= "and r.reviews_id = rd.reviews_id and "; 
	$parentQuery .= "rd.languages_id = '1' order by r.reviews_id asc;";
	
	$parentResultset = tep_db_query($parentQuery);
	
	$childReviewPadding = 0;
	
	$reviewHandler = new review_Handler($parentResultset);
?>
									<tr>
									<td style="padding-top: 30px">
										
		  <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
      	  <td colspan="2" class="main" align="right" width="150px"><?php echo '<a href="' . 
			tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $review['products_id']) . 
			'&review_id='.$HTTP_GET_VARS['review'].'">' . 
			tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '<br/>Reply to this review!</a>';?>
		  </td>
          </tr>
          <tr>
      	  <td colspan="2" style="width: 100%; height: 40px; padding: 10px 0 5px 2px; border-bottom:1px solid lightgray;margin-bottom: 20px">
      	   <h1 style="vertical-align: middle;color:#135383"> Reviews </h1>
      	  </td>
          </tr>
          <?php
          	
          	$parent = $reviewHandler->getParent($parentResultset, $review_id);
			$reviewHandler->writeToPage($parent);   // write parent 
          	$reviewHandler->printChildren($parentResultset,$parent['reviews_id']);     // write children               	
          ?>
        </table>
										
		</td>
		</tr>
		<tr>
			<td><?php //echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>
		</tr>
		<tr>
			<td>
			<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
				<tr class="infoBoxContents">
					<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							
							<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, 'products_id=' . $review['products_id']) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '<br/>All Reviews</a>';?></td>
							<td class="main" align="right" width="150px"><?php echo '<a href="' . 
								tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $review['products_id']) . 
								'">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '<br/>Write a Review!</a>';?></td>
							
						</tr>
					</table></td>
				</tr>
			</table></td>
		</tr>
								</table><h3>Related Categories</h3><?php
										include (DIR_WS_MODULES . 'products_categories.php');
									?><?php related_product_categories($review['products_id']);?></td>
						</td>
						<td width="<?php echo SMALL_IMAGE_WIDTH + 10;?>" align="right" valign="top">
						<table border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td align="center" class="smallText"><?php
if (tep_not_null($review['products_image'])) {
								?>
								<script language="javascript">
<!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $review['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $review['products_image'], addslashes($review['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>';?>
	');
	//-->
								</script>
								<noscript>
									<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $review['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $review['products_image'], $review['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>';?>
								</noscript><?php
								}
								?></td>
							</tr>
						</table></td>
				</table></td>
			</tr>
		</table></td>
		<!-- body_text_eof //-->
		</TR></TABLE>
		<!-- body_eof //-->
		<!-- footer //-->
		<?php
			require (DIR_WS_INCLUDES . 'footer.php');
		?>
		<!-- footer_eof //-->
		<br>
	</body>
</html>
<?php
	require (DIR_WS_INCLUDES . 'application_bottom.php');
?>
