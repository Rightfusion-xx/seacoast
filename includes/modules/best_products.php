
<!-- new_products //-->
<?php
  $info_box_contents = array();
  if ( (isset($new_products_category_id) && $new_products_category_id!='0')) {
     $info_box_contents[] = array('text' => sprintf('Bestselling & Favorites'));
    }else{
    $info_box_contents[] = array('text' => sprintf('<h2>Our Bestselling Natural Health Supplements</h2>'));
    }

  new contentBoxHeading($info_box_contents);

 if ( (isset($new_products_category_id) && $new_products_category_id!='0')) {
     $sql = "select distinct p.products_id, pd.products_name, 
          if(s.status, s.specials_new_products_price, p.products_price) as products_price, (SELECT CAST(AVG(reviews_rating) as signed) FROM reviews WHERE products_id=p.products_id) as reviews_rating
          from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id join " . TABLE_PRODUCTS_TO_CATEGORIES . 
          " p2c on p2c.products_id=p.products_id join " . TABLE_CATEGORIES . " c on c.categories_id=p2c.categories_id left outer join " . TABLE_SPECIALS . " s on p.products_id = s.products_id 
          where p.products_status = '1' and p.products_ordered > 0 and 
          pd.language_id = '" . (int)$languages_id . "' and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) 
          order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS;
          }
    else{
    $sql = "select distinct p.products_id, p.products_image, p.products_model, p.products_tax_class_id, 
      if(s.status, s.specials_new_products_price, p.products_price) as products_price, mnf.manufacturers_name, (SELECT CAST(AVG(reviews_rating) as signed) FROM reviews WHERE products_id=p.products_id) as reviews_rating,
      rvw.reviews_rating from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id join " . 
      TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p2c.products_id=p.products_id join " . TABLE_CATEGORIES . " c on c.categories_id=p2c.categories_id left join ".TABLE_MANUFACTURERS." mnf on p.manufacturers_id = 
      mnf.manufacturers_id left join ".TABLE_REVIEWS." rvw on p.products_id = rvw.products_id where 
      c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' 
      order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS;
  } 
    $new_products_query = tep_db_query($sql);
  
  $row = 0;
  $col = 0;
  $info_box_contents = array();
  echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="scv_bestprod_box"><tr>';
  
  while ($new_products = tep_db_fetch_array($new_products_query)) {
  
  $new_products['products_name'] = tep_get_products_name($new_products['products_id']);

	$reviews_rating = $new_products['reviews_rating'];
	$manufacturers_name = $new_products['manufacturers_name'];

	if(!$manufacturers_name==null) {
	  $manufacturers_name = $manufacturers_name;
	} else {
	  $manufacturers_name = '';
	}

	if(!$reviews_rating==null){
	$reviews_rating = '<img src="images/stars_'. $new_products['reviews_rating'].'.gif" alt="'.$new_products['reviews_rating'] . TABLE_HEADING_TEXT_OF_5_STARS . ' ('.$new_products['products_name'].')" border="0" align="absmiddle">';
	}else {
	$reviews_rating = '<img src="images/stars_0.gif" alt="Not Rated" border="0" align="absmiddle"><br><span class="smallText"><a rel="nofollow" href="product_reviews_write.php?products_id='.$new_products['products_id'].'">' . TABLE_HEADING_FIRST_TO_RATE . '</a></span>';
	}

    //Get image location
    $product_image_path=select_image($new_products['products_id'], $new_products['products_image'],  $new_products['manufacturers_id']);


$info_box_contents[$row][$col] = array('align' => 'left', 
'params' => 'class="smallText" width="20%" valign="top"',
                                           'text' => ' <!-- one TBL Product -->
		<table width="100%" border="0" cellspacing="1" cellpadding="4" bgcolor="#B2B2B2">
		  <tr>
		  <td bgcolor="#F5F5F5" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a></td>
		  </tr>
		  <tr>
		  	<td bgcolor="#FFFFFF">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		        <tr>
				  <td align="left" style="padding:3px;"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . str_replace('images/','',$product_image_path), $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>
				  <td height="125" valign="top">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
					    <td height="17" class="main" class="scv_manuname">' . $manufacturers_name . '</td>
					  </tr>
					  <tr>
						<td height="17" class="main" class="scv_price"><b>' . TABLE_HEADING_PRICE . ': <font color="#FF5C02">' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])). '</font></b></td>
					  </tr>
					  <tr>
					    <td height="17" class="main"><b>' . TABLE_HEADING_RATING . ': </b>'.$reviews_rating.'</td>
					  </tr>
					</table>
				  </td>
				</tr>
				<tr>
				  <td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>
				   <td height="30"><a rel="nofollow" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products['products_id']) . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_BUY_NOW) . '</a>
				</tr>
			  </table>
			</td>
		  </tr>
		</table>
		<!-- one TBL Product -->');

    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  new contentBox($info_box_contents);
  //echo $info_box_contents[0][0]['text'];
?>
<!-- new_products_eof //-->