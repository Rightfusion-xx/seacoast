<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  $num_listings=200;
  
    //check to see if URL was remoded                                
  redirect_moded_url();
  
  
  if($pagenum<1)
  {
      redir301('/');
  }
   
$cache=new megacache(60*60*24);
if(!$cache->doCache('catalog',true))
{  
  include_once('includes/functions/render_products.php');
  
   
    $select_column_list = '';
          $select_column_list .= ' p.products_msrp, pd.products_name, pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'm.manufacturers_name, products_image, ';
        $listing_sql = "select " . $select_column_list . " pd.products_isspecial, case when pd.products_ailments like 
        '" . tep_db_input($useName) . "%' then 1 else 2 end as priority, pd.products_head_desc_tag as product_desc, 
        pd.products_uses, pd.products_ailments, pd.products_departments, p.products_id, p.manufacturers_id, p.products_price, 
        p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, 
        IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd 
        JOIN  " . TABLE_PRODUCTS . " p on p.products_id=pd.products_id left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id 
        left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and 
        pd.language_id = '" . (int)$languages_id . "'";
  $listing_sql .= " order by products_name asc";
    $disableoutput=true;
    
    //Manufacturer name workaround
    $_REQUEST['manufacturers_id']=1;
    
    
    include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);  
  
  
if(strlen($lastprod))
{
    $title='Nutritional Supplements '. ucwords(substr($firstprod,0,3)) . '-' . ucwords(substr($lastprod,0,3));
    $description='Catalog of supplements beginning from  ' . ucwords(substr($firstprod,0,3)) . ' to  ' . ucwords(substr($lastprod,0,3));
    $page_title=$title;
    $linktext=ucwords(substr($firstprod,0,3)) . ' - ' . ucwords(substr($lastprod,0,3));
    
    try
    {
        $ac=automated_catalog::find($pagenum);  
        if($ac->linktext!=$linktext)
        {
            $ac->linktext=$linktext;
            $ac->save();
        }
       
    }
    catch (Exception $e)
    {
        $ac=automated_catalog::create(Array('pagenum'=>$pagenum,'linktext'=>$linktext));
        
    }
  }
  else
  {
      try
    {
        $ac=automated_catalog::find($pagenum);  
        $ac->delete();
        
    }
    catch(Exception $e)
    {
        
    }
      
      redir301('/');
  }
  
  
  //check for bad URLs
  if(seo_url_title($linktext)<>$url_title)
  {
    redir301($processor.seo_url_title($linktext,$pagenum));
  }

  
  


?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title>
<?php echo $title?>
</title>
<meta name="description" content="<?php echo $description?>"/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


<div id="content">
    
    <h1><?php echo $page_title?></h1>
    <?php


    if(strlen($listing_text))
    {
       echo '<p>'.$listing_text.'</p>';
       echo $paging;
    }
        ?>


	<br style="clear:both;"/>
	</div>  
     
    
<?php 

$cache->addCache('catalog');
}


require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

