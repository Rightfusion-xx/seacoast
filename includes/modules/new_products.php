<?php
/*
  $Id: new_products.php,v 2.0 2006/11/13 10:42:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<!-- new_products //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf('<h2>New Additions and Changes</h2>', strftime('%B')));

  new contentBoxHeading($info_box_contents);

 if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
     $sql = "select distinct p.products_id, mnf.manufacturers_id, p.products_image, p.products_model, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price, mnf.manufacturers_name, 
     CASE WHEN p.products_last_modified > p.products_date_added AND p.products_last_modified>rvw.date_added 
THEN p.products_last_modified
WHEN p.products_date_added > p.products_last_modified AND p.products_date_added>rvw.date_added THEN p.products_date_added
ELSE rvw.date_added END AS modified_date from " . TABLE_PRODUCTS . " p left join ".TABLE_SPECIALS." s on p.products_id = s.products_id left join ".TABLE_MANUFACTURERS." mnf on p.manufacturers_id = mnf.manufacturers_id left join ".TABLE_REVIEWS." rvw on p.products_id = rvw.products_id where (p.products_last_modified=NULL OR p.products_last_modified>=DATE_ADD(CURDATE(), INTERVAL -7 DAY)) AND p.products_status = '1' order by modified_date desc limit ".MAX_DISPLAY_NEW_PRODUCTS;
    $new_products_query = tep_db_query($sql);
  
  } else {
    $sql = "select distinct p.products_id, mnf.manufacturers_id, p.products_image, p.products_model, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price, mnf.manufacturers_name, 
    CASE WHEN p.products_last_modified > p.products_date_added AND p.products_last_modified>rvw.date_added 
THEN p.products_last_modified
WHEN p.products_date_added > p.products_last_modified AND p.products_date_added>rvw.date_added THEN p.products_date_added
ELSE rvw.date_added END AS modified_date 
     from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p2c.products_id=p.products_id join " . TABLE_CATEGORIES . " c on c.categories_id=p2c.categories_id left join ".TABLE_MANUFACTURERS." mnf on p.manufacturers_id = mnf.manufacturers_id left join ".TABLE_REVIEWS." rvw on p.products_id = rvw.products_id where and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' order by modified_date desc limit " . MAX_DISPLAY_NEW_PRODUCTS;
    $new_products_query = tep_db_query($sql);
	}
  
  $row = 0;
  $col = 0;
  $info_box_contents = array();
  echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
  
  while ($new_products = tep_db_fetch_array($new_products_query)) {
  
    $product_image_path='';
  
$product_image_path=select_image($new_products['products_id'], $new_products['products_image'],  $new_products['manufacturers_id']);

  
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
	$reviews_rating = '<img src="images/stars_0.gif" alt="Not Rated" border="0" align="absmiddle">';
	}

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
					    <td height="17" class="main">' . $manufacturers_name . '</td>
					  </tr>
					  <tr>
						<td height="17" class="main"><b>' . TABLE_HEADING_PRICE . ': <font color="#FF5C02">' . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])). '</font></b></td>
					  </tr>
					  <tr>
					    <td height="17" class="main"><b>' . TABLE_HEADING_RATING . ': </b>'.$reviews_rating.'</td>
					  </tr>
					</table>
				  </td>
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
