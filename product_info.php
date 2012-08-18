<?php

require('includes/application_top.php');

// HEY! YOU PROBABLY WANT PRODUCTS_INFO2.php.
// BUT LEAVE THIS PAGE. IT REDIRECTS.

// check for moded url
redirect_moded_url();

if(strpos($_SERVER['HTTP_USER_AGENT'],"seacoast-crawler")>0)
{
     $seacoast_crawler=true;
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

if((int)$HTTP_GET_VARS['products_id']==CM_FTPID || (int)$HTTP_GET_VARS['products_id']==CM_PID){
	redir301('/community/');
}

$product_info_query = tep_db_query("select pd.products_target_keyword, p.products_keywords, p.products_die, p.products_sku, p.products_upc,
						p.products_dieqty, pd.products_head_title_tag, pd.products_head_keywords_tag,
						pd.products_head_desc_tag, pd.products_type,
						pd.products_departments,pd.products_ailments,pd.products_uses,
						p.products_weight, p.products_ordered, pd.products_head_keywords_tag,
						pd.products_viewed, date_format(p.products_date_added,'%m/%d/%Y') as
						products_date_added, p.products_last_modified,
						p.products_id, pd.products_name, pd.products_description, p.products_model,
						p.products_quantity, p.products_image, pd.products_url, p.products_msrp,
						p.products_price, p.products_tax_class_id, p.products_date_available,
						p.manufacturers_id, m.manufacturers_name, pd.products_takeaway
						from " . TABLE_PRODUCTS . " p join  " . TABLE_PRODUCTS_DESCRIPTION . " pd on
						p.products_id=pd.products_id join ". TABLE_MANUFACTURERS ." m on m.manufacturers_id=p.manufacturers_id
						where p.products_status = '1' and p.products_id = '" . (int)$_REQUEST['products_id'] .
	"' and pd.language_id =' " . (int)$languages_id . "'");


if(!($product_info = tep_db_fetch_array($product_info_query))){
    //No product found, redirect.
    redir301(HTTP_SERVER);
}
else
{


  if(strpos(' '.$_SERVER['HTTP_USER_AGENT'],'gsa-crawler')>0 )
  {
    include(DIR_WS_INCLUDES.'create_crawler_page.php');
    exit();
  }


  

    //Get product name
    $products_name = $product_info['products_name'];


  $product_parts=parse_nameparts($product_info['products_name']);
$tname=$product_parts['name'];
$tmisc=$product_parts['attributes'];
$shortname=$tname;

    //check URL  - Send to new URL processed by produccts_info2.php
$test_url=(str_replace('//','/',"/".seo_url_title($tname)."/".seo_url_title($product_info["manufacturers_name"])."/".seo_url_title($tmisc)."/p".$product_info['products_id']));
  if($test_url<>$_SERVER["REQUEST_URI"])
  {
      redir301(str_replace('//','/',"/".seo_url_title($tname)."/".seo_url_title($product_info["manufacturers_name"])."/".seo_url_title($tmisc)."/p".$product_info['products_id']));
  }
                                
  exit();
}