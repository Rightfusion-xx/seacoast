<?php



  require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');



// the following cPath references come from application_top.php

  $category_depth = 'top';

  if (isset($cPath) && tep_not_null($cPath)) {

    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");

    $cateqories_products = tep_db_fetch_array($categories_products_query);

    if ($cateqories_products['total'] > 0) {

      $category_depth = 'products'; // display products

    } else {

      $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "'");

      $category_parent = tep_db_fetch_array($category_parent_query);

      if ($category_parent['total'] > 0) {

        $category_depth = 'nested'; // navigate through the categories

      } else {

        $category_depth = 'products'; // category has no products, but display the 'no products' message

      }

    }

  }



  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<?php



// BOF: WebMakers.com Changed: Header Tag Controller v1.0



// Replaced by header_tags.php



if ( file_exists(DIR_WS_INCLUDES . 'header_tags.php') ) {



  require(DIR_WS_INCLUDES . 'header_tags.php');



} else {



?>

<title>

<?php echo TITLE ?>

</title>

<?php



}





?>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">

<link rel="stylesheet" type="text/css" href="/stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!--
request type=<?php echo $request_type?>
https=<?php echo $_SERVER['HTTPS']?>
-->

 



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

<?php

   if ($category_depth == 'nested') {
    $category_query = tep_db_query("select cd.categories_name, c.categories_image, cd.categories_htc_title_tag, cd.categories_htc_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");

    $category = tep_db_fetch_array($category_query);

?>

    <td width="100%" valign="top">



    <table border="0" width="100%" cellspacing="0" cellpadding="7">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td><h1><?php echo $category['categories_htc_title_tag']; ?></h1></td>
           <td class="pageHeading" align="right"><?php echo // tep_image(DIR_WS_IMAGES . $category['categories_image'], $category['categories_name'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
           <?php if (tep_not_null($category['categories_htc_description'])) { ?> 
          <tr>
           <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
           <td style="font-face:Arial;font-size:10pt;"><?php echo $category['categories_htc_description']; ?></td>
          </tr>
          <?php } ?>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

          <tr>

            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="subCategory">

              <tr>

<?php

    if (isset($cPath) && strpos('_', $cPath)) {

// check to see if there are deeper categories within the current category

      $category_links = array_reverse($cPath_array);

      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {

        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");

        $categories = tep_db_fetch_array($categories_query);

        if ($categories['total'] < 1) {

          // do nothing, go through the loop

        } else {

          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");

          break; // we've found the deepest category the customer is in

        }

      }

    } else {

      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");

    }



    $number_of_categories = tep_db_num_rows($categories_query);



    $rows = 0;

    while ($categories = tep_db_fetch_array($categories_query)) {

      $rows++;

      $cPath_new = tep_get_path($categories['categories_id']);

      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

      echo '                <td align="left" class="largeText" width="' . $width . '" valign="top"><ul><li><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . 

	                        //tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br>' . 

							$categories['categories_name'] . '</a></ul></td>' . "\n";

      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {

        echo '              </tr>' . "\n";

        echo '              <tr>' . "\n";

      }

    }



// needed for the new products module shown below

    $new_products_category_id = $current_category_id;

?>

              </tr>

            </table></td>

          </tr>

          <tr>

            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

          </tr>

           <tr>

            <td><?php include(DIR_WS_MODULES . FILENAME_PRODUCT_SPECIALS); ?></td>

          </tr>

          <tr>

            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

          </tr>

		  <tr>

            <td><?php include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); ?></td>

          </tr>

        </table></td>

      </tr>

    </table></td>







<?php

  } elseif ($category_depth == 'products' || isset($HTTP_GET_VARS['manufacturers_id'])) {
if (isset($HTTP_GET_VARS['manufacturers_id'])) 
      $db_query = tep_db_query("select manufacturers_htc_title_tag as htc_title, manufacturers_htc_description as htc_description from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$languages_id . "' and manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    else 
      $db_query = tep_db_query("select categories_htc_title_tag as htc_title, categories_htc_description as htc_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");

    $htc = tep_db_fetch_array($db_query);

// create column list

    $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,

                         'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,

                         'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,

                         'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,

                         'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,

                         'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,

                         'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,

                         'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);



    asort($define_list);



    $column_list = array();

    reset($define_list);

    while (list($key, $value) = each($define_list)) {

      if ($value > 0) $column_list[] = $key;

    }



    $select_column_list = '';



    for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {

      switch ($column_list[$i]) {

        case 'PRODUCT_LIST_MODEL':

          $select_column_list .= 'p.products_model, ';

          break;

        case 'PRODUCT_LIST_NAME':

          $select_column_list .= 'pd.products_name, ';

          break;

        case 'PRODUCT_LIST_MANUFACTURER':

          $select_column_list .= 'm.manufacturers_name, ';

          break;

        case 'PRODUCT_LIST_QUANTITY':

          $select_column_list .= 'p.products_quantity, ';

          break;

        case 'PRODUCT_LIST_IMAGE':

          $select_column_list .= 'p.products_image, ';

          break;

        case 'PRODUCT_LIST_WEIGHT':

          $select_column_list .= 'p.products_weight, ';

          break;

      }

    }



// show the products of a specified manufacturer

    if (isset($HTTP_GET_VARS['manufacturers_id'])) {

      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {

// We are asked to show only a specific category

        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'";

      } else {

// We show them all

        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";

      }

    } else {

// show the products in a given categorie

      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {

// We are asked to show only specific catgeory

        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";

      } else {

// We show them all

        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";

      }

    }



    if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {

      for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {

        if ($column_list[$i] == 'PRODUCT_LIST_NAME') {

          $HTTP_GET_VARS['sort'] = $i+1 . 'a';

          $listing_sql .= " order by pd.products_name";

          break;

        }

      }

    } else {

      $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);

      $sort_order = substr($HTTP_GET_VARS['sort'], 1);

      $listing_sql .= ' order by ';

      switch ($column_list[$sort_col-1]) {

        case 'PRODUCT_LIST_MODEL':

          $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_NAME':

          $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');

          break;

        case 'PRODUCT_LIST_MANUFACTURER':

          $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_QUANTITY':

          $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_IMAGE':

          $listing_sql .= "pd.products_name";

          break;

        case 'PRODUCT_LIST_WEIGHT':

          $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

        case 'PRODUCT_LIST_PRICE':

          $listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";

          break;

      }

    }

?>

    <td width="100%" valign="top">



    <table border="0" width="100%" cellspacing="0" cellpadding="7">

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td align="left" valign="top"><h1><?php echo $htc['htc_title']; ?></h1>

			<?php

			  $heading = '';

    if (isset($HTTP_GET_VARS['manufacturers_id=60'])) {

      $heading = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");

	  $heading = tep_db_fetch_array($heading);

      $heading = $heading['manufacturers_name'];

    } elseif ($current_category_id) {

	  $heading = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "'");

      $heading = tep_db_fetch_array($heading);

      $heading = $heading['categories_name'];

    }

	?>
<td align="right"><?php echo tep_image(DIR_WS_IMAGES . $image, $category['categories_htc_title_tag'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
         </tr>                     <?php if (tep_not_null($htc['htc_description'])) { ?> 
         <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <tr>
         <td colspan="2" style="font-family:arial;font-size:12pt;padding-bottom:10px;"><?php echo $htc['htc_description']; ?></td>
        </tr>
         <?php } ?>
			

			<?php

// optional Product List Filter

    if (PRODUCT_LIST_FILTER > 0) {

      if (isset($HTTP_GET_VARS['manufacturers_id'])) {

        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "' order by cd.categories_name";

      } else {

        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";

      }

      $filterlist_query = tep_db_query($filterlist_sql);

      if (tep_db_num_rows($filterlist_query) > 1) {

        echo '            <td align="right" class="main" valign="top">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';

        if (isset($HTTP_GET_VARS['manufacturers_id'])) {

          echo tep_draw_hidden_field('manufacturers_id', $HTTP_GET_VARS['manufacturers_id']);

          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));

        } else {

          echo tep_draw_hidden_field('cPath', $cPath);

          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));

        }

        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);

        while ($filterlist = tep_db_fetch_array($filterlist_query)) {

          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);

        }

        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');

        echo '</form></td>' . "\n";

      }

    }



// Get the right image for the top-right

 //   $image = DIR_WS_IMAGES . 'table_background_list.gif';

 //   if (isset($HTTP_GET_VARS['manufacturers_id'])) {

 //     $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");

 //     $image = tep_db_fetch_array($image);

 //     $image = $image['manufacturers_image'];

 //   } elseif ($current_category_id) {

	//  $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");

 //     $image = tep_db_fetch_array($image);

 //     $image = $image['categories_image'];

 //   }

 

  ?>           



          </tr>

        </table></td>

      </tr>

      <tr>




        <td><?php //echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <?php 

/* Begin: Product Specials  */ 

	if (SHOW_PRODUCT_SPECIALS_ON_PRODUCTS_LIST=='true') {

?>

          <tr>

            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

          </tr>

          <tr>

            <td><?php include(DIR_WS_MODULES . FILENAME_PRODUCT_SPECIALS); ?></td>

          </tr>

<?php 

	}

/* End: Product Specials  */ ?>



	  <tr>

        <td><?php include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING); ?></td>

      </tr>

    </table></td>



<?php

  } else { // default page

if (isset($HTTP_GET_VARS['manufacturers_id'])) 
      $db_query = tep_db_query("select manufacturers_htc_title_tag as htc_title, manufacturers_htc_description as htc_description from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . (int)$languages_id . "' and manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    else 
      $db_query = tep_db_query("select categories_htc_title_tag as htc_title, categories_htc_description as htc_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' and language_id = '" . (int)$languages_id . "'");

    $htc = tep_db_fetch_array($db_query);
    ?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
                  <h1 align="center">
                    <?php echo $htc['htc_title']; ?>
                  </h1>
                </td>

          </tr>
        </table></td>

      </tr>

      

      <tr>

        <td>

            <table border="0" width="100%" cellspacing="0" cellpadding="5">

              <tr> 

                <td class="main"> 
                  <div align="center"><font face="Arial, Helvetica, sans-serif" size="3"> 
                    <?php require('register.php')?>
                    </font> </div>
                  <p align="left"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    Enter the Seacoast Vitamins' Allergy Free Summer Sweepstakes 
                    for your chance to win a<b> $500 Gift Certificate to SeacoastVitamins.com</b> 
                    and dozens of special allergy care packs to help you stay 
                    Allergy Free this summer. By signing up for the Allergy Free 
                    Summer Sweepstakes you will also be subscribed to the SeacoastVitamins.com 
                    Natural Health Newsletter. This newsletter contains special 
                    deals, savings and information only available to Natural Health 
                    Newsletter subscribers. The Natural Health Newsletter is absolutely 
                    free and you can cancel your subscription at any time by clicking 
                    on the 'unsubscribe' link in the newsletter. Enjoy these and 
                    other great offers from SeacoastVitamins.com and stay Allergy 
                    Free this Summer. </font></p>
                  <div align="left"> 
                    <p><font face="Arial, Helvetica, sans-serif" size="2"><b>Grand 
                      Prize:</b> $500 Gift Certificates to Seacoast Vitamins<b> 
                      (1 Winner)</b><br>
                      <b>Other Prizes: </b>100 People will win an Allergy Care 
                      Packages! <b>(10 Winners Per Week for 10 Weeks)</b><br>
                      - Each Allergy Care Package will include at least three 
                      of the listed products under the Sponsored By Section (Right 
                      Column)<br>
                      The Drawing for the $500.00 Gift Certificate will be held 
                      on July 2, 2007 winners will be notified by email. <br>
                      <b> *If you won an Care Package you are still eligible to 
                      win the $500.00 Gift Certificates</b></font></p>
                    <p><img src="images/bannerall.jpg" width="420" height="93"></p>
                    <p><img src="images/naturalfactslogo1.bmp" alt="Natural Factors"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1316">Aller 
                      7 Formula</a></b> is a natural way to deal with allergy 
                      symptoms. Aller-7 is a special blend of seven herbal extracts 
                      that have shown to promote<br>
                      healthy respiration and immunity.<br>
                    </p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=446">Lung, 
                      Bronchial &amp; Sinus Health</a></b> one of the most important 
                      features of healthy airways is the elasticity and fluidity 
                      of the respiratory tract secretion. With Dr. Murrary's Lung, 
                      Bronchial &amp; Sinus Health there is a safe and effective 
                      natural product that can improve these secretions and as 
                      a result lead to easier breathing.<br>
                    </p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1907">Quercetin</a> 
                      </b>found in blue-green algae, has a beneficial effect on 
                      numerous ailments. It modulates allergic responses!<br>
                    </p>
                    <p><img src="images/naturesway.gif"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=450">HAS 
                      Original Blend</a></b> Non-Stimulant Nasal Decongestant 
                    </p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=174">Alpha 
                      SH</a></b> All Natural &amp; Homeopathic, is recommended 
                      for relief of acute sinusitis and sinus related headaches 
                      due to congestion.</p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=173">Allergiemittle</a></b> 
                      Do you have itchy, watery eyes, Do you deal with nasal congestion 
                      and runny nose?</p>
                    <p><img src="images/newchapter.gif"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=2207">Chinese 
                      Skull Cap</a></b> Cap Chinese Skullcap-Anti-inflammatory 
                      similar in effect to Prednisone, blocks histamine release, 
                      5-10 times more potent than prescription anti allergenic 
                      drug Azelastine, suppressed inflammatory release in asthmatic 
                      patients, used as a treatment for allergies and asthma in 
                      Japan. 3 per day works best for allergies</p>
                    <p></p>
                    <p><img src="images/nllogo.gif"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1697">Bromelain 
                      Sinus Ease</a></b> contains three key ingredients that have 
                      been shown to enhance the body's ability to reduce this 
                      natural inflammatory response and help clear up sinuses.<br>
                    </p>
                    <p><img src="images/solaraylogo.gif"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1985">QBC 
                      Plex</a></b> is one of the most well known non-citrus bioflavonoids. 
                      Flavonoids help maintain normal capillary permeability. 
                      Bromelain is the famous enzyme from pineapple that has been 
                      valued for many years. Vitamin C is an important nutrient 
                      intended to provide nutritive support for normal, healthy 
                      collagen synthesis, development of <br>
                      the cartilage and bone, capillary and blood vessel integrity, 
                      health of your skin, and nerve impulse transmission.<br>
                    </p>
                    <p><img src="images/we_mn_logo_01.gif"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=2060">Wobenzym 
                      N Seven</a></b> the enzymes in Wobenzym N tablets work throughout 
                      the body to support the immune system and maintain normal 
                      inflammation levels. Wobenzym N creme temporarily eases 
                      discomfort associated with arthritis pain, muscle pain, 
                      sports injuries and other conditions involving inflammation. 
                      This unique combination of mega-dose tablets and pain relieving 
                      creme is formulated to help your body heal itself without 
                      side effects. Give Wobenzym a week and get on with your 
                      life!<br>
                    </p>
                    <p><img src="images/LiddellLogo.gif"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1652">Allergy 
                      Spray</a></b> fast relief for allergy and hay fever discomfort. 
                      We don't know what's worse, the dull heavy headache, the 
                      runny nose or the breathing difficulties. But we do know 
                      hay fever and allergies can make your life unbearable. This 
                      convenient oral spray makes it easy to get the safe and 
                      natural holistic relief you need. This great product works 
                      AMAZINGLY FAST - usually within minutes!<br>
                    </p>
                    <p><img src="images/natrabio.jpg"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=186">Cold 
                      &amp; Sinus Nasal Spray</a></b> Get relief from your cold 
                      and flu-like symptoms with Natra-Bio Cold and Sinus Nasal 
                      Spray. This homeopathic remedy is made of entirely natural 
                      ingredients that make it effective and safe for continuous 
                      use. It relieves nasal congestion, runny nose, sneezing, 
                      headache, and sinus pressure<br>
                    </p>
                    <p><img src="images/Boiron.png"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=1839">Chestol 
                      Honey + Oscillo 3 Dose</a></b> temporarily relieves cough 
                      due to minor throat and bronchial irritation as may occur 
                      with a cold; helps loosen phlegm (mucus) and thin bronchial 
                      secretions to make coughs more productive.</p>
                    <p></p>
                    <p><img src="images/seacoast.gif"></p>
                    <p><b><a href="http://www.seacoastvitamins.com/product_info.php?products_id=937">Bromelain</a></b> 
                      may help to &#147;dissolve&#148; the foreign Proteins (Antigens) 
                      that are responsible for many Allergies</p>
                  </div>
                </td>

              </tr>



              <tr> 

                <td> 

                  <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

                </td>

              </tr>



              <tr> 

                <td> 

                  <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

                </td>

              </tr>

              <tr> 

                <td> 

                  <?php 



            // include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); 



           // if ($new_products_category_id > 0) {



	       //      include(DIR_WS_MODULES . FILENAME_NEW_PRODUCTS); 



          //  } else {



	     //      include(DIR_WS_MODULES . FILENAME_YOURFRONTPAGE); 



         //}



                 ?>

                </td>

              </tr>

              <tr> 

                <td> 

                  <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

                </td>

              </tr>

              <tr> 

                <td class="main" align="center"> 

                <?php // require(DIR_WS_INCLUDES . 'front.php'); ?>

                </td>

              </tr>

              <?php

   // include(DIR_WS_MODULES . FILENAME_UPCOMING_PRODUCTS);

?>

            </table>

          </td>

      </tr>

    </table></td>



<?php

  }

?>

<!-- body_text_eof //-->

   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">

     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">

<!-- right_navigation //-->

<?php require(DIR_WS_INCLUDES . 'sweepstakes_column_right.php'); ?>

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

