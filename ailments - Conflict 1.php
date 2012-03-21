<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  include_once('includes/functions/render_products.php');
  

  $useName=str_replace('&amp;','&',ucwords($_REQUEST['remedy'].''));
  $products_id=(int)$_REQUEST['products_id'];

  
  if(!strlen($useName) && !(int)$_REQUEST['products_id'])
  {
    redir301('/ailments-diseases/');
  }

  if((int)$_REQUEST['products_id'])
  {
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
       $title='Ailments & Indications to Use '.$product_info['manufacturers_name']. ' '. $product_info['products_name'];
       populate_backlinks();
       $description='Certain home remedies using '.$product_info['manufacturers_name']. ' '. $product_info['products_name']. ' may aid
                             in personal recovery.';
    }
    
  }
  else
  {
    $title='Remedies & Natural Cures for '.$useName;
    $description='Supplements, natural remedies & cures for '.$useName.' that may aid in recovery.';
    $select_column_list = '';
          $select_column_list .= ' p.products_msrp, pd.products_name, pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'm.manufacturers_name, products_image, ';


        $listing_sql = "select " . $select_column_list . " pd.products_isspecial, case when pd.products_ailments like '" . tep_db_input($useName) . "%' then 1 else 2 end as priority, pd.products_head_desc_tag as product_desc, pd.products_uses, pd.products_ailments, pd.products_departments, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd JOIN  " . TABLE_PRODUCTS . " p on p.products_id=pd.products_id left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' and pd.products_ailments like '%" . tep_db_input($useName) . "%'";
        $listing_sql .= " order by manufacturers_name asc, products_name asc";
    $disableoutput=true;
    include(DIR_WS_MODULES . FILENAME_PRODUCT_LISTING);  
  }



if(strlen($lastprod))
{
  if(isset($_REQUEST['page']))
  {
    $title=$useName.' Remedies from '. $firstprod . ' to ' . $lastprod;
    $description='Explore ' . $useName . ' products including ' . $lastprod . ' and ' . $firstprod;
    $page_title=$title;
  }
  else
  {
    $page_description='<p>The following list contains the <b>best supplements</b> for ' .$useName.' relief.</p>';
    $page_title='Natural Remedies, Cures & Supplements for ' . $useName;
  }
  
  $page_description.='<p>Starting with '.$useName.' products <b>1.) ' . $firstprod . '</b> through <b>' .$rows.'.) '. $lastprod . '</b>.</p>';

}



?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title>
<?php echo $title?>
</title>
<meta name="keywords" content="<?php echo $useName, ',',$lastprod,',',$firstprod;?>"/>
<meta name="description" content="<?php echo $description?>"/>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<?php if(!$products_id)
{                           ?>

<div id="content">
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
     else
     {
       // No need to pull ailments. Already have them.
       ?>
       <div id="content">
            <h1>Ailments & Uses for <?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></h1>
            
            <p style="width:600px;">
                Certain uses and indications are associated with
                <a href="/product_info.php?products_id=<?php echo $product_info['products_id'];?>"><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></a>.
                This may be a partial list of potential uses. This list is not verified, and is not reviewed by the FDA.
            </p>
            <?php

                 if(strlen($product_info['products_ailments']))
                 {
                   $ailarr=explode(',',$product_info['products_ailments']);
                   asort($ailarr);
                   foreach($ailarr as $item)
                   {
                    $item=trim($item);
                    $mflink=link_exists('/ailments.php?remedy='.urlencode(strtolower($item)),$page_links) ;
                    $mflink=strlen($mflink) ? $mflink : '/ailments.php?remedy='.urlencode(strtolower($item));

                    ?>
                    <p><a href="<?php echo $mflink;?>"><?php echo $item?></a></p>
                    
                    <?php



                    }?>
                     <p style="width:600px;">
            To view a list of all ailments and diseases, <a href="/symptoms/">click here</a>.

            </p>      <?php
                 }
                 else
                 {
                  ?>
                   <p style="width:600px;">
                      No ailments or uses are currently associated with 
                      <a href="/product_info.php?products_id=<?php echo $product_info['products_id'];?>"><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></a>.
                      To view a list of all cataloged ailments and uses, <a href="/ailments.php">click here</a>.

                   </p>
                  <?php

                 }

            ?>       
       </div>  
       <?php

     }
     
  

      ?>  
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

