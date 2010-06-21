<?php


require('includes/application_top.php');

$product_info_query = tep_db_query("select p.products_keywords, pd.products_head_title_tag, pd.products_head_keywords_tag,
						pd.products_head_desc_tag, pd.products_type, psu.product_sku,
						pd.products_departments,pd.products_ailments,pd.products_uses,
                                                date_format(p.products_date_added,'%m/%d/%Y') as
						products_date_added, p.products_last_modified, psu.product_upc,
						p.products_id, pd.products_name, pd.products_description, p.products_model,
						p.products_quantity, p.products_image, pd.products_url, p.products_msrp,
						p.products_price, p.products_tax_class_id, p.products_date_available,
						p.manufacturers_id, m.manufacturers_name
						from " . TABLE_PRODUCTS . " p join  " . TABLE_PRODUCTS_DESCRIPTION . " pd on
						p.products_id=pd.products_id join ". TABLE_MANUFACTURERS ." m on m.manufacturers_id=p.manufacturers_id
						left outer JOIN products_sku_upc psu ON psu.product_ids = p.products_id where p.products_status = '1' and p.products_id = '" . (int)$_REQUEST['products_id'] . 
	"' and pd.language_id =' " . (int)$languages_id . "'");


if(!($product_info = tep_db_fetch_array($product_info_query))){
    //No product found, redirect.
    redir301(HTTP_SERVER);
}
else
{

}

 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo 'Popular topics that reference ' , $product_info['products_name'] , ' from ' , $product_info['manufacturers_name']?> </title>
<meta name="keywords" content=""/>
<meta name="description" content="The following list of <?php echo $product_info['products_name']; ?> references show popular ways to locate this product."/>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<?php if($cart->count_contents() < 1 && $_SERVER['HTTPS']=='off' && $no_results){?>
<div name="chitika" style="margin:30px 0 30px 0;text-align:center;display:block;">
 <!-- Chitika -->
<script type="text/javascript"><!--
ch_client = "NealBozeman";
ch_type = "mpu";
ch_width = 550;
ch_height = 250;
ch_non_contextual = 4;
ch_vertical ="premium";
ch_sid = "Topic Pages";
var ch_queries = new Array( );
var ch_selected=Math.floor((Math.random()*ch_queries.length));
if ( ch_selected < ch_queries.length ) {
ch_query = ch_queries[ch_selected];
}
//--></script>
<script  src="http://scripts.chitika.net/eminimalls/amm.js" type="text/javascript">
</script>   

</div>

<?php } ?>


<div id="content">


      <h1><?php echo 'Popular topics that reference ' , $product_info['products_name'] , ' from ' , $product_info['manufacturers_name']?> </title></h1>
      
      <p style="width:600px;"> These are the most popular keyword searches used to locate
       <a href="/product_info.php?products_id=<?php echo $product_info['products_id'];?>"><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></a>.
       Click any link below for additional information and related health topics.
     </p>

      <?php include(DIR_WS_MODULES . 'similar_picks.php');?>

</div>


<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>


</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

