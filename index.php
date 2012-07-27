<?php



  require('includes/application_top.php');

  // check for moded url
  redirect_moded_url();


  if(!strlen($_SERVER['QUERY_STRING']))
  {
  	redir301('/');
  }

  //Get the first letter of the category we're in
    if(strlen($url_title))
    {
        preg_match('/[a-z]/i',$url_title, $letter);
        $letter=trim($letter[0]);
    }

  include_once('includes/functions/render_products.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);


// the following cPath references come from application_top.php
  $title=TITLE;
  $title_tag='';
  $cPath=(int)$_REQUEST['cPath'];
  $manufacturers_id=(int)$_REQUEST['manufacturers_id'];
  $products_id=(int)$_REQUEST['products_id'];



  if($products_id)
  {

      //redirect to appropriate product.

      // Deprecated - 301 products_id pages to product page.
      $products_name=tep_db_fetch_array(tep_db_query("select concat(manufacturers_name,\" \",products_name) as products_name from products_description pd join products p on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id where p.products_id=".(int)$products_id));
      $replacement="/supplement/".seo_url_title($products_name["products_name"]."-".$products_id, $page);
      redir301($replacement);


    /*
    if(!($product_info = tep_db_fetch_array($product_info_query))){
        //No product found, redirect.
        redir301(HTTP_SERVER);     */
    }
    else
    {
       $title='Health Guides Featuring '.$product_info['manufacturers_name']. ' '. $product_info['products_name'];
       populate_backlinks();
       $description='The following resources explore '.$product_info['manufacturers_name']. ' '. $product_info['products_name']. 'in
                         depth through health guides, related materials and important information.';
    }




if ($manufacturers_id)
{
  $db_query = tep_db_query("select manufacturers_name, manufacturers_name as htc_title, manufacturers_htc_description as htc_description from " . TABLE_MANUFACTURERS_INFO . " mi join manufacturers m on m.manufacturers_id=mi.manufacturers_id where languages_id = '" . (int)$languages_id . "' and mi.manufacturers_id = '" . (int)$manufacturers_id . "'");
  $manufacturers_name=tep_db_fetch_array(tep_db_query('select manufacturers_name from manufacturers where manufacturers_id=' . (int)$manufacturers_id));
  if(seo_url_title($manufacturers_name['manufacturers_name']." ".$manufacturers_id)<>$url_title)
  {
    redir301($processor.seo_url_title($manufacturers_name['manufacturers_name']." ".$manufacturers_id,$pagenum));
  }
}
elseif($cPath)
{
  $db_query = tep_db_query("select categories_htc_title_tag as htc_title, categories_htc_description as htc_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath . "' and language_id = '" . (int)$languages_id . "'");
}

if($cPath || $manufacturers_id)
{
    $htc = tep_db_fetch_array($db_query);
    $title=$htc['htc_title'];
    // create column list

    $select_column_list = '';
              $select_column_list .= 'p.products_model, ';
              $select_column_list .= 'pd.products_name, pd.products_head_desc_tag as product_desc, pd.products_uses, pd.products_ailments, pd.products_departments,  ';
              $select_column_list .= 'pd.products_head_desc_tag as product_desc, ';
              $select_column_list .= 'm.manufacturers_name, ';
              $select_column_list .= 'p.products_quantity, ';
          $select_column_list .= 'p.products_image, ';
          $select_column_list .= 'p.products_weight, ';
    // show the products of a specified manufacturer

    if ($manufacturers_id) {

        $listing_sql = "select " . $select_column_list . " p.products_image, p.products_image, p.products_msrp,  p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id=pd.products_id join " . TABLE_MANUFACTURERS . " m on m.manufacturers_id=p.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and m.manufacturers_id = '" . (int)$manufacturers_id . "' group by products_id order by manufacturers_name asc, products_name asc ";

      }elseif($cPath){
// We show them all products for the category
      $listing_sql = "select " . $select_column_list . " p.products_msrp, p.products_image,  p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd join " . TABLE_PRODUCTS . " p on p.products_id=pd.products_id left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id join " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p2c.products_id=p.products_id left outer join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$cPath . "' group by products_id order by manufacturers_name asc, products_name asc ";

      }

   if ($cPath) {
         $category = tep_db_query("select categories_htc_title_tag, categories_htc_description, categories_htc_desc_tag, categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath . "'");
         $category = tep_db_fetch_array($category);
         $heading = $category['categories_name'];
         if(seo_url_title($heading." ".$cPath)<>$url_title)
         {
             redir301($processor.seo_url_title($heading." ".$cPath,$pagenum));
         }
         $title=!isset($_REQUEST['page']) ? $category['categories_htc_title_tag'] : $heading;
         $description=!isset($_REQUEST['page']) ? $category['categories_htc_desc_tag'] : '';
        $title_tag=!isset($_REQUEST['page']) ? $category['categories_htc_title_tag'] : $category['categories_name'];
        $page_description=!isset($_REQUEST['page']) ? $category['categories_htc_description'] : '';

         if(strlen($description)<1)
         {
           $description='Get acquainted with ' . $heading . ' products, including ';
         }
       }

    if((int)$cPath || (int)$manufacturers_id)
    {
      $disableoutput=true;

      include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);

      if(strlen($firstprod))
      {
         if($manufacturers_id or isset($_REQUEST['page']))
         {
          $title.=', ' . $firstprod . ' to ' . $lastprod;
          $title_tag.=', ' . $firstprod . ' to ' . $lastprod;
         }

         if($manufacturers_id || isset($_REQUEST['page']))
         {
           $description.=$lastprod . ' & ' . $firstprod;
         }
         if($manufacturers_id)
         {
             $title_tag=$title;
            if(!isset($_REQUEST['page']))
            {
              $page_description=$htc['htc_description'];
            }

          }
          $page_description.='<p>Now displaying products <b>'.(($listing_split->current_page_number*$listing_split->number_of_rows_per_page-$listing_split->number_of_rows_per_page)+1) .
          '.) ' . $firstprod . '</b> through <b>' .(($listing_split->current_page_number*$listing_split->number_of_rows_per_page)-($listing_split->number_of_rows_per_page-$rows)).'.) '. $lastprod . '</b> out of <b>'.$listing_split->number_of_rows.' total.</b></p>';



    }
    }
}

if(!$products_id && !strlen($lastprod) && !cPath)
{
    //No products found, so redirect
    redir301('/health-guides/');
    exit();
}


?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>

<title><?php echo $title ?></title>
<meta name="description" content="<?php echo $description; ?>"/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<link rel="stylesheet" type="text/css" href="/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">



<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="content">

<?php if($cPath)
{
    ?>
    <p><a href="/health-guides/<?php echo strtolower($letter)?>/">Health Guides Index Starting From "<?php echo strtoupper($letter);?>"</a></p>

    <?php

}
?>
<?php if($cPath || $manufacturers_id){ ?>
<p><?php echo $breadcrumb->trail(' &raquo; '); ?>

<h1><?php echo $title_tag; ?></h1>

<p><?php echo $page_description; ?></p>

<?php } ?>

<?php

 if($cPath)
 {
     $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id=cd.categories_id where c.parent_id = '" . (int)$current_category_id . "' and  cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
     $number_of_categories = tep_db_num_rows($categories_query);

     $rows = 0;
     if($number_of_categories)
     {
       echo '<p><ul>';
    while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = tep_get_path($categories['categories_id']);
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

      echo '<li><a href="' . tep_href_link(FILENAME_DEFAULT,'cPath='. $categories['categories_id']) . '">' .
							$categories['categories_name'] . '</a></li>' . "\n";
      }
      echo '</ul></p>';
     }
 }

    ?>


<?php

  if($products_id) // call up related product categories.
  {
    // pull up product categories from DB
    $cat_query=tep_db_query('SELECT cd.categories_id, cd.categories_name, cd.categories_htc_desc_tag from categories_description cd
                                    join products_to_categories p2c on p2c.categories_id=cd.categories_id
                                    where p2c.products_id='.(int)$_REQUEST['products_id'].' order by categories_name asc');

        ?>
        <h1><?php echo $product_info['manufacturers_name'], ' ',$product_info['products_name'], ' Health Guides';?></h1>

        <p style="width:600px;"> Health Guides are simply categories in which <a href="/product_info.php?products_id=<?php echo $product_info['products_id'];?>"><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></a>
        can be located, along with additional resources and related products.
       </p>



        <?php
        //Spool categories and intertwine with departments
        $categories=array();
        while($cat=tep_db_fetch_array($cat_query))
        {
            array_push($categories,$cat);
        }
        foreach(explode(',',$product_info['products_departments']) as $item)
        {
            array_push($categories, array('categories_id'=>0, 'categories_name'=>ucwords(trim($item)), 'categories_htc_desc_tag'=>''));
        }

        $found=false;
        foreach($categories as $cat)
        {
            if($cat['categories_id']==0)
            {
                //Display a link to a department
                $mflink=link_exists('/departments.php?benefits='.urlencode(strtolower($cat['categories_name'])),$page_links) ?
                                                                                     link_exists('/departments.php?benefits='.urlencode(strtolower($cat['categories_name'])),$page_links) :
                                                                                     '/departments.php?benefits='.urlencode(strtolower($cat['categories_name']));
            }
            else
            {
                //Display a link to a category page
                $mflink=link_exists('/index.php?cPath='.$cat['categories_id'],$page_links) ?
                                                                                     link_exists('/index.php?cPath='.$cat['categories_id'],$page_links) :
                                                                                     '/index.php?cPath='.$cat['categories_id'];
            }

          if(!$found)
          {
            echo '<div><ul>';
          }
          $found=true;
          ?>
          <li><p><?php echo '<a href="',$mflink,'">',$cat['categories_name'],'</a>';
          if(strlen($cat['categories_htc_desc_tag'])>0){ echo ' - ',$cat['categories_htc_desc_tag']; }
          ?>
          </p> </li>

          <?php

          if(!$found)
          {    ?>
            <p>Sorry. No categories were found for this product.</p>
                      <?php
          }

        }

        if($found)
        {
          echo '</ul></div>';
        }

        ?>
        <p>To view all major health guides, <a href="/health-guides/">click here</a>.</p>


        <?php
    }

    if(strlen($listing_text))
    {
       echo '<p>'.$listing_text.'</p>';
       echo $paging;
    }
        ?>

    </div>

    <br style="clear:both"/>



<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

