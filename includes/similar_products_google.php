<?php
   


?>
<!-- similar_products //-->
<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header">Similar Items</div>
  <?php

  mini_prods();
    function mini_prods(){
        $index=0;
        global $currencies;
        global $products_name;
        $ptemp=$products_name . '(';
        $pname=trim(substr($ptemp,0,strpos($ptemp,'(')));
        $page_param=parse_section(str_replace('&','<eof>',$_SERVER['REQUEST_URI']).'<eof>','?','<eof>');
        
        if(strlen($pname)>0){
        
		    $gquery=GOOGLE_SEARCH_URL . "num=4&filter=0&q=inurl%3Aproduct_info+" . urlencode($pname);
		    $products=file_get_contents($gquery);
		    		$total_prods=(int)parse_section($products, '<M>','</M>');
		if($total_prods>=1)
		{
		?>
<div id="mini-prod-list" style="width:300px;margin-top:0px;">
  <?php
		    //Show all the products
		    $index=1;
		    
		    $sdelim='<R N="' . $index . '">';
		    $curres=parse_section($products, $sdelim, '</R>');
		    
   $rows=1;

		    do{
		        
		        $pid=parse_section($curres, 'product_info.php?products_id=','</U>');
		        if($pid!=$_REQUEST['products_id']){
		        if($product_info=tep_db_fetch_array(tep_db_query('SELECT products_image, p.products_id, p.products_msrp, pd.products_head_desc_tag as product_desc, p.products_price, products_name, manufacturers_name, m.manufacturers_id
		                            from products p join products_description pd on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id
		                            where p.products_id=\''.$pid.'\' and products_status=1'))){;
   $listing_text='';
   
   
      
      $rows++;

      $product_image_path='';
      
      if($rows<=20){

$product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);
       

}
  


      $listing_text.='<div id="prod'.$rows.'" class="';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.='mini-product_isspecial';
      }else{
        $listing_text.='mini-product_regular';
      }
      $listing_text.='">';
            
            $listing_text.='<div class="mini-listing-image">';
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
   
           $listing_text.='<br><br><br style="clear:both;"/>';



  
      $listing_text.='</div>';

    }		       }
    echo $listing_text;
    $listing_text='';
		        
		        $index++;
    		    
		        $sdelim='<R N="' . $index . '">';
		        $curres=parse_section($products, $sdelim, '</R>');
    		    
		    }
		    while(strlen($curres)>1 && $rows<=3);
		    
		   
		if($index>0){ echo '<a href="/topic.php?health='.strtolower(urlencode($pname)).'" style="margin-left:5px;">More...</a></div>';}
		}
		}
}

?>
</div>
<!-- similar_products_eof //-->
