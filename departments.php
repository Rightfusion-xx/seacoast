<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  // check for moded url
  redirect_moded_url();                                                                                               
  
  include_once('includes/functions/render_products.php');
 
  $useName=ucwords(str_replace('-',' ',$_REQUEST['benefits'].'')); 
  
  //Get the first letter of the category we're in
    if(strlen($useName))
    {
        preg_match('/[a-z]/i',$useName, $letter);
        $letter=trim($letter[0]);
    }
  
  if(!strlen($useName))
  {
    redir301('/');
  }
  
  $title=$useName.' Supplements';
  $description='The best '. $useName .' vitamin supplements.';

    $select_column_list = '';
          $select_column_list .= ' p.products_msrp, pd.products_name, pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'm.manufacturers_name, products_image, ';


        $listing_sql = "select " . $select_column_list . " pd.products_head_desc_tag as product_desc, pd.products_uses, 
            pd.products_ailments, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, 
            IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, 
            IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd 
            JOIN  " . TABLE_PRODUCTS . " p on p.products_id=pd.products_id left join " . TABLE_MANUFACTURERS . " m on 
            p.manufacturers_id = m.manufacturers_id 
            left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id 
            where p.products_status = '1' 
            and pd.language_id = '" . (int)$languages_id . "' and pd.products_departments like '%" . str_replace(' ','%',tep_db_input($useName)) . "%'"
            . " and pd.products_departments rlike '(,[:space:]*|^[:space:]*).{0,3}".str_replace(' ','.{1,3}',tep_db_input($useName)).".{0,3}([:space:]*,[:space:]*|[:space:]*$)'
            ";
        $listing_sql .= " order by pd.products_isspecial desc, pd.products_name";
        
        
   
   $disableoutput=true;
   include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);


if(strlen($lastprod))
{
  if(isset($_REQUEST['page']))
  {
    $title=$firstprod . ' to ' . $lastprod .' for ' . $useName;
    $description= $useName .' supplements including ' . $lastprod . ' and ' . $firstprod . '. Explore all '.$useName.' supplements.';
    $page_title=$title;
  }
  else
  {
    $page_description='<p>Browse supplements & natural products for <b> ' .$useName.'</b>.</p>';
    $page_title='' . $useName . ' Supplements & Natural Products';
  }
  
  $page_description.='<p>Showing <b>'.$useName.' '.(($listing_split->current_page_number*$listing_split->number_of_rows_per_page-$listing_split->number_of_rows_per_page)+1) . 
  '.) ' . $firstprod . '</b> through <b>' .(($listing_split->current_page_number*$listing_split->number_of_rows_per_page)-($listing_split->number_of_rows_per_page-$rows)).'.) '. $lastprod . '</b> out of <b>'.$listing_split->number_of_rows.' total items.</b></p>';

}
else
{
    //No products found, so redirect
    redir301('/health-guides/'.strtolower($letter).'/');
    exit();    
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
<meta name="keywords" content="<?php echo $useName?>"/>
<meta name="description" content="<?php echo $description;?>"/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div id="content">
<p><a href="/health-guides/<?php echo strtolower($letter)?>/">Health Guides Index Starting From "<?php echo strtoupper($letter);?>"</a></p>

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

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

