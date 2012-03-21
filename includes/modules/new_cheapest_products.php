

<!-- new_products //-->
<?php
$isbot=false;
 if ( strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'googlebot')>0 ) {
    $isbot=true;
    $sql = "select *, products_manufacturer as manufacturers_name from products_new order by rand() limit ".MAX_DISPLAY_NEW_PRODUCTS;
    $new_products_query = tep_db_query($sql);
  
  } else {
    $sql = "select *, products_manufacturer as manufacturers_name from products_new order by time_featured desc, rand() limit ".MAX_DISPLAY_NEW_PRODUCTS;
    $new_products_query = tep_db_query($sql);
	}
  
  $row = 0;
  $col = 0;
  $info_box_contents = array();
  echo '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
  
  while ($new_products = tep_db_fetch_array($new_products_query)) {
  
  if($isbot==true)
  {
    tep_db_query('update products_new set time_featured="'.date(DATE_ATOM).'" where products_id='.tep_db_input($new_products['products_id']));
  
  }
  
  $new_products['products_name'];

	$reviews_rating = $new_products['reviews_rating'];
	$manufacturers_name = $new_products['manufacturers_name'];

	if(!$manufacturers_name==null) {
	  $manufacturers_name = UCWords(strtolower($manufacturers_name));
	} else {
	  $manufacturers_name = '';
	}

	if(!$reviews_rating==null){
	$reviews_rating = '<img src="images/stars_'. $new_products['reviews_rating'].'.gif" alt="'.$new_products['reviews_rating'] . TABLE_HEADING_TEXT_OF_5_STARS . ' ('.$new_products['products_name'].')" border="0" align="absmiddle">';
	}else {
	$reviews_rating = '<img src="images/stars_0.gif" alt="Not Rated" border="0" align="absmiddle"><br><span class="smallText"><a href="/cheapest/'.$new_products['products_id'].'-'.format_seo_url($new_products['products_name']).'">' . TABLE_HEADING_FIRST_TO_RATE . '</a></span>';
	}

$info_box_contents[$row][$col] = array('align' => 'left', 
'params' => 'class="smallText" width="20%" valign="top"',
                                           'text' => ' <!-- one TBL Product -->
		<table width="100%" border="0" cellspacing="1" cellpadding="4" bgcolor="#B2B2B2">
		  <tr>
		  <td bgcolor="#F5F5F5" class="main"><a href="/cheapest/'.$new_products['products_id'].'-'.format_seo_url($new_products['products_name']).'">'.$new_products['products_name'].'</a></td>
		  </tr>
		  <tr>
		  	<td bgcolor="#FFFFFF">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		        <tr>
				  <td align="left" style="padding:3px;"><a href="/cheapest/'.$new_products['products_id'].'-'.format_seo_url($new_products['products_name']).'">' . tep_image($new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>
				  <td height="125" valign="top">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
					    <td height="17" class="main">' . $manufacturers_name . '</td>
					  </tr>
					  <tr>
						<td height="17" class="main"><b>' . TABLE_HEADING_PRICE . ': <font color="#FF5C02">' . $currencies->display_price($new_products['products_offer_low'], tep_get_tax_rate($new_products['products_tax_class_id'])). '</font></b></td>
					  </tr>
					  <tr>
					    <td height="17" class="main"><b>' . TABLE_HEADING_RATING . ': </b>'.$reviews_rating.'</td>
					  </tr>
					</table>
				  </td>
				</tr>
				<tr>
				  <td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>
				   <td height="30">
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