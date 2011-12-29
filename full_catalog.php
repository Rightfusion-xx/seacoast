<?php


  require('includes/application_top.php');
  include_once('includes/functions/render_products.php');






  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>
<title>Complete Vitamin Supplement Catalog</title>
 <meta name="Description" content="A-Z index catalog of nutritional and natural health products." />
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<div id="content">

                       <h1>Complete Vitamin Supplement Catalog</h1>
    <h2  style="clear:both;">Best Selling Supplements</h2>

<?php
  $useCategories[]='';
  $ailmentCategories[]='';
  $departmentCategories[]='';

    $select_column_list = '';
          $select_column_list .= ' p.products_id, products_image, p.products_price, p.products_msrp, pd.products_name, pd.products_head_desc_tag as product_desc, ';
          $select_column_list .= 'pd.products_head_desc_tag as product_desc, pd.products_isspecial, ';
          $select_column_list .= 'm.manufacturers_name, m.manufacturers_id, products_image, pd.products_ailments, pd.products_uses, pd.products_departments, ';


$listing_sql='select ' . $select_column_list .'  sum(op.products_quantity)  as velocity,IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price 
    from products p join products_description pd on pd.products_id=p.products_id join orders_products op on op.products_id=p.products_id
      join orders o on o.orders_id=op.orders_id join manufacturers m on m.manufacturers_id=p.manufacturers_id
    LEFT OUTER join specials s on s.products_id=p.products_id
    where op.products_id=p.products_id and date_purchased>=curdate()- INTERVAL 30 DAY and orders_status=3
    group by p.products_id
    order by velocity desc
    limit 0,25';
        
    $rows=1;
   $listing_sql=tep_db_query($listing_sql);
   while($product_info=tep_db_fetch_array($listing_sql))
   {
         $listing_text='';
         //Get all related ailments, uses, and departments.
      $tempuses=preg_split('/,/',str_replace(', ',',',$product_info['products_uses']));
      foreach($tempuses as $tempitem)
      {
        if(array_search($tempitem,$useCategories)==false && strlen($tempitem)>2){
          array_push($useCategories,ucwords($tempitem));
        }
      }
      
      $tempuses=preg_split('/,/',str_replace(', ',',',$product_info['products_ailments']));
      foreach($tempuses as $tempitem)
      {
        if(array_search($tempitem,$ailmentCategories)==false && strlen($tempitem)>2){
          array_push($ailmentCategories,ucwords($tempitem));
        }
      }   
      
      $tempuses=preg_split('/,/',str_replace(', ',',',$product_info['products_departments']));
      foreach($tempuses as $tempitem)
      {
        if(array_search($tempitem,$departmentCategories)==false && strlen($tempitem)>2){
          array_push($departmentCategories,ucwords($tempitem));
        }
      }   
   
      
      $rows++;

      $product_image_path='';
      
     
        $product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);

  


      $listing_text.='<div id="prod'.$rows.'" class="';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.='mini-product_isspecial';
      }else{
        $listing_text.='mini-product_regular';
      }
      $listing_text.='">';
            
            $listing_text.='<div style="float:left;" class="mini-listing-image">';
            if(strlen($product_image_path)>0){
             $listing_text.='<img src="'. $product_image_path . '" width="50" style="margin:5px;" ALIGN="left" />';
            }
            $listing_text.='</div>';

            $listing_text.= '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '"><b>' . $product_info['products_name'] . '</b></a>';
            
            $listing_text.='<br/><span style="color:#66CC00;font-weight:bold;">';
            if (tep_not_null($product_info['specials_new_products_price'])) {

              $listing_text.= '&nbsp;<s>' .  $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['specials_new_products_price'])/$product_info['products_msrp']*100);}
            } else {

              $listing_text.= '&nbsp;' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['products_price'])/$product_info['products_msrp']*100);}

            }

           $listing_text.='</span> from <b>';
           $listing_text.= $product_info['manufacturers_name'].'</b>' ;

            if($discountpct>0){
             $listing_text .= '<br/><span style="color:#ff0000;font-weight:bold;">'.$discountpct.'% Off</span>';
            }
   
           $listing_text.='<br style="clear:both;"/>';



  
      $listing_text.='</div>';

               
    echo $listing_text;
    $listing_text='';
                
      
      $rows++;
   
   
   }
   
    


?>                   
          
          <br style="clear:both;"/>             
                   
   <h2>Catalog of Nutritional Supplements</h2>
    <p>
    <?php
    
    foreach(automated_catalog::all() as $catalog)
    {
        
        echo '<a href="/catalog.php?page='.$catalog->pagenum.'">'.$catalog->linktext.'</a> &nbsp; ';
    }    

?>
    
    
    </p>
</div>  

<?php 


require(DIR_WS_INCLUDES . 'footer.php'); ?>

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

