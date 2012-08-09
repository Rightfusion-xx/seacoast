<?php

  require('includes/application_top.php');
  
  $product_info_query = tep_db_query("select m.manufacturers_name, pd.products_head_desc_tag, p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p join  " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id JOIN manufacturers m on m.manufacturers_id=p.manufacturers_id where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "'");
  if (isset($HTTP_GET_VARS['review_id']))
  {
  	$review_info = 
  	tep_db_fetch_array(tep_db_query("select rd.use from reviews_description rd where reviews_id = ". $HTTP_GET_VARS['review_id']));
	
	$autofilledTitle = (trim(substr($review_info['use'], 0, 3) == "Re:")) ? $review_info['use'] : 'Re: '.$review_info['use'];
  }
  
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) 
  {  	
  	$rating = tep_db_prepare_input($HTTP_POST_VARS['rating']);
    $review = tep_db_prepare_input($HTTP_POST_VARS['review']);
    $name = tep_db_prepare_input($HTTP_POST_VARS['FirstName']);
    $keywords = tep_db_prepare_input($HTTP_POST_VARS['keywords1'].','.$HTTP_POST_VARS['keywords2'].','.$HTTP_POST_VARS['keywords3']);
    $use = tep_db_prepare_input($HTTP_POST_VARS['use']);
	
	$valid = validateInputs($review, $rating);
	if ($valid) 
    {    			
      tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . tep_db_input($name) . "', '" . tep_db_input($rating) . "', now())");
      $insert_id = tep_db_insert_id();

      tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text, keywords, reviews_description.use) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "','" . tep_db_input($keywords) . "', '" . tep_db_input($use) . "')");

      tep_redirect(tep_href_link('/product_reviews_write_successful.php', 'review=' . $insert_id));
    }
  }
  elseif (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'reply')) 
  {
  	$rating = tep_db_prepare_input($HTTP_POST_VARS['rating']);
    $review = tep_db_prepare_input($HTTP_POST_VARS['review']);
    $name = tep_db_prepare_input($HTTP_POST_VARS['FirstName']);
    $keywords = tep_db_prepare_input($HTTP_POST_VARS['keywords1'].','.$HTTP_POST_VARS['keywords2'].','.$HTTP_POST_VARS['keywords3']);
    $use = tep_db_prepare_input($HTTP_POST_VARS['use']);
  	
  	$valid = validateInputs($review, $rating);
	if ($valid) 
    {
    	$product_id = $HTTP_GET_VARS['products_id'];
    	$review_id = $HTTP_GET_VARS['review_id'];
		
		$query =  "insert into " . TABLE_REVIEWS . " (products_id, customers_name, reviews_rating, date_added,review_parent_id) values ('";
		$query .= $product_id . "', '" . tep_db_input($name) . "', '" . tep_db_input($rating) . "', now(),". $review_id. ")"; 
		
		tep_db_query($query);
        $insert_id = tep_db_insert_id();
		
		$query = "";
		
		$query = "insert into " .TABLE_REVIEWS_DESCRIPTION ." (reviews_id,languages_id,reviews_text,keywords,reviews_description.use) values ('";
        $query .= (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "','" . tep_db_input($keywords);
		$query .= "', '" . tep_db_input($use) . "')"; 
        tep_db_query($query);
        
		tep_redirect(tep_href_link('/product_reviews_info.php', 'review=' . $insert_id));
    }
  }

	function validateInputs($review, $rating)
	{
		global $messageStack;
		
		$error = 0;
				
		if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
	      	
	      $error++;
	      $messageStack->add('review', JS_REVIEW_TEXT);
	    }
	    if (strpos($review,'href')>0) {
	      $error++;
	      $messageStack->add('review', 'Link tags are not accepted in reviews.');
	    }
	    if (($rating < 1) || ($rating > 5)) {
	      $error++;
	      $messageStack->add('review', JS_REVIEW_RATING);
	    }
		return $error == 0;
	}

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
    $products_name = '<h1 style="display:inline;">Write a Review for '.$product_info['products_name'] . ' from ' . $product_info['manufacturers_name'].'</h1><br/><span class="smallText">[' . $product_info['manufacturers_name'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.min.css">
    <link href="/css/main.css" rel="stylesheet">
    <link href="/font/fonts.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/jquery/respond.src.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Write Review for <?php echo $product_info['products_name']; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if (use.length < 10 ?>) {
    error_message = error_message + "<?php 'Please enter a longer /"use/" description.' ; ?>";
    error = 1;
  }
  
  if (FirstName.length < 1 ?>) {
    error_message = error_message + "<?php 'Please enter your first name or alias.' ; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

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
    <td width="100%" valign="top"><div id="content">
		
		<?php 
			if (isset($HTTP_GET_VARS['review_id']))
			{
				echo tep_draw_form('product_reviews_write', 
		   		 tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=reply&products_id=' . 
				 $HTTP_GET_VARS['products_id'].'&review_id='.$HTTP_GET_VARS['review_id']), 
				 'post', 'onSubmit="return checkForm();"'); 
			}
			else
			{
				echo tep_draw_form('product_reviews_write', 
		   		 tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . 
				 $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"');
			}
		 ?>
	  
	  <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><?php echo $products_name; ?>
            <p>
            <?php echo $product_info['products_head_desc_tag']; ?>
            </p>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if ($messageStack->size('review') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('review'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main">
                	<b>First Name</b><br/><input type="text" name="FirstName" value="<?php echo($_REQUEST['FirstName']); ?>"><p/><b><br />
                What do you use it for?
                </b> (ex. "Help reduce cholesterol")<br/>
                <input type="text" name="use" value="<?php echo $autofilledTitle ; ?>" size="78">
              <p/><br />
                <b><?php echo SUB_TITLE_REVIEW; ?></b><br/>
              <?php echo tep_draw_textarea_field('review', 'soft', 60, 15); ?><p/>
                      <p>
                      <b>Optional: Help others find your review.</b></br/>Enter Descriptive Keywords (ex. heart, cholesterol, arteries)<br/>
                      <input type="text" name="keywords1" value="<?php echo $_REQUEST['keywords1']?>">
                      <input type="text" name="keywords2" value="<?php echo $_REQUEST['keywords2']?>">
                      <input type="text" name="keywords3" value="<?php echo $_REQUEST['keywords3']?>">
                      </p>
                     <img src="/images/thumbs_up.gif"> <input type="radio" name="rating" value="5" <?php if($_REQUEST['rating']=='5' || !isset($_REQUEST['rating'])){ echo 'checked'; } ?> > <b>I RECOMMEND this product!</b> 
                     <br/>
                     <img src="/images/thumbs_down.gif"> <input type="radio" name="rating" value="1" <?php if($_REQUEST['rating']=='1' && isset($_REQUEST['rating'])){ echo 'checked'; } ?> > <b>I DO NOT recommend this product.</b> 
                    </td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
              </tr>
            </table></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" align="right" valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" class="smallText">
<?php
  if (tep_not_null($product_info['products_image'])) {
?>
<script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
</noscript>
<?php
  }

?>
                </td>
              </tr>
            </table>
          </td>
        </table></td>
      </tr>
    </table></form><hr /></div>
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
