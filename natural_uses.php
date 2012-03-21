<?php
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
include_once('includes/functions/render_products.php');


$useName=ucwords(str_replace('-',' ',$_REQUEST['use'].''));
$products_id=(int)$_REQUEST['products_id'];

if(!strlen($useName) && !(int)$_REQUEST['products_id'])
{
  redir301('/symptoms/');
}

if((int)$_REQUEST['products_id'])
{
      $products_name=tep_db_fetch_array(tep_db_query("select concat(manufacturers_name,\" \",products_name) as products_name from products_description pd join products p on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id where p.products_id=".(int)$_REQUEST['products_id']));                                                                        
      $replacement="/supplement/".seo_url_title($products_name["products_name"]."-".$products_id, $page);
      redir301($replacement);

  if(!($product_info = tep_db_fetch_array($product_info_query))){
      //No product found, redirect.
      redir301(HTTP_SERVER);
  }
  else
  {
     $title='Benefits from '. $product_info['products_name'].' by '.$product_info['manufacturers_name'];
     populate_backlinks();
     $description='Editor and community ideas for '. $product_info['products_name']. ' uses, made by '.$product_info['manufacturers_name'].'.';
  }
  
}
else
{
  $title='Natural Support for '.$useName;
  $description='Specialized products for '. $useName .' use.';
  
  $select_column_list = '';
    $select_column_list .= 'pd.products_name, pd.products_head_desc_tag as product_desc, ';
    $select_column_list .= 'pd.products_head_desc_tag as product_desc, ';
    $select_column_list .= 'm.manufacturers_name, products_image, ';


  $listing_sql = "select " . $select_column_list . " pd.products_isspecial, case when 
                pd.products_ailments like '" . tep_db_input($useName) . "%' then 1 else 2 end as 
                priority, p.products_msrp,  pd.products_head_desc_tag as product_desc, pd.products_uses, 
                pd.products_ailments, pd.products_departments, p.products_id, p.manufacturers_id, 
                p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, 
                NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, 
                p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd JOIN  " . 
                TABLE_PRODUCTS . " p on p.products_id=pd.products_id left join " . TABLE_MANUFACTURERS . 
                " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on 
                p.products_id = s.products_id where p.products_status = '1' and pd.language_id = '" . 
                (int)$languages_id . "' and pd.products_uses like '%" . str_replace(' ','%',tep_db_input($useName)) . "%'"
                . " and pd.products_uses rlike '(,[:space:]*|^[:space:]*).{0,3}".str_replace(' ','.{1,3}',tep_db_input($useName)).".{0,3}([:space:]*,[:space:]*|[:space:]*$)'";
  $listing_sql .= " order by manufacturers_name asc,  pd.products_name";


   $disableoutput=true;
   include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);


}

//Get the first letter of the category we're in
if(strlen($useName))
{
    preg_match('/\s[a-z]/i',$useName, $letter);
    $letter=trim($letter[0]);
}

if(strlen($lastprod))
{
  if(isset($_REQUEST['page']))
  {
    $title=$firstprod . ' to ' . $lastprod .' for ' . $useName;
    $description='Products like ' . $lastprod . ' or ' . $firstprod . ' are used for ' . $useName . '. Find out which work the best.';
    $page_title=$title;
  }
  else
  {
    $page_description='<p>Powerful nutritional vitamin supplements that may help with <b> ' .$useName.'</b>.</p>';
    $page_title='Natural Remedies, Cures & Supplements for ' . $useName;
  }                                                                                                                                         
  
  $page_description.='<p>Showing <b>'.(($listing_split->current_page_number*$listing_split->number_of_rows_per_page-$listing_split->number_of_rows_per_page)+1) . 
  '.) ' . $firstprod . '</b> through <b>' .(($listing_split->current_page_number*$listing_split->number_of_rows_per_page)-($listing_split->number_of_rows_per_page-$rows)).'.) '. $lastprod . '</b> out of <b>'.$listing_split->number_of_rows.' total items</b> to help with '.$useName.'.</p>';

}
elseif(!$products_id)
{
    //No products found, so redirect
    redir301('/symptoms/'.strtolower($letter).'/');
    exit();    
}   

//query is cached, check to see if URL is correct                                                                            
if(!$modURL && !isset($_REQUEST['products_id']))
{
    //correct modified URL was not entered, so redirect
    redir301('/remedies/'.(seo_url_title($useName,(int)$_REQUEST['page'])));
    
}


?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php


?>
<title>
 <?php echo $title ?>
</title>
<meta name="keywords" content="<?php echo $useName, ',',$lastprod,',',$firstprod;?>"/>
<meta name="description" content="<?php echo $description?>"/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php');
if(!$products_id)
  {

?>
<div id="content">
<p><a href="/symptoms/<?php echo strtolower($letter)?>/">Symptoms Starting With "<?php echo strtoupper($letter);?>"</a></p>
    <h1><?php echo $page_title?></h1>
    <?php echo $page_description;


    if(strlen($listing_text))
    {
       echo '<p>'.$listing_text.'</p>';
       echo $paging;
    }
        ?>

        <br style="clear:both;"/>
	</div>
     

      <?php }
           ?>
      

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

