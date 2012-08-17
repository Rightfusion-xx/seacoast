<?php
   


?>
<!-- similar_products //-->
  <?php
  if(GOOGLE_MINI_SERVING==false){return(0);  }
  $ctx=stream_context_create(array(
                                     'http' => array(
                                     'timeout' => 3
                                     )
                                     )
                                     );
                                     
    mini_prods_new();
  
    function mini_prods_new(){
        $index=0;
        global $ctx;
        global $currencies;
        global $products_name;
        $ptemp=$products_name . '(';
        $pname=trim(substr($ptemp,0,strpos($ptemp,'(')));
        $page_param=parse_section(str_replace('&','<eof>',$_SERVER['REQUEST_URI']).'<eof>','?','<eof>');
        
        if(strlen($pname)>0){

		    $gquery=GOOGLE_SEARCH_URL . "num=7&filter=0&q=inurl%3A/cheapest/+" . urlencode($pname);
		    @$products=file_get_contents($gquery, 0, $ctx);
		    		$total_prods=(int)parse_section($products, '<M>','</M>');
		if(1==1)
		{
		?>
 <h2>More Products</h2> <?php
		    //Show all the products
		    $index=1;
		    
		    $sdelim='<R N="' . $index . '">';
		    $curres=parse_section($products, $sdelim, '</R>');
		    
   $rows=1;
   global $products_id;

		    do{
		        
		        $pid=parse_section($curres, '/cheapest/','-');
		        if($pid!=$products_id && is_numeric($pid)){
		            if($product_info=tep_db_fetch_array(tep_db_query('SELECT * from products_new
		                                where products_id="'.$pid.'"'))){
          $listing_text='';
   
   
      
      $rows++;

      $product_image_path='';
      


      $listing_text.='<div style="white-space:normal;" id="newprod'.$rows.'" class="';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.='mini-product_isspecial';
      }else{
        $listing_text.='mini-product_regular';
      }
      $listing_text.='">';
            
            $listing_text.='<div class="listing-image">';
            if(strlen($product_info['products_image'])>0){
             $listing_text.='<img src="'. $product_info['products_image'] . '" width="50" style="margin:5px;" ALIGN="left" />';
            }
            $listing_text.='</div>';

            $listing_text.= '&nbsp;<a href="/cheapest/' . $product_info['products_id'] . '-'.format_seo_url($product_info['products_name']).'"><b>' . $product_info['products_name'] . '</b></a>';
            
            $listing_text.='<br/><span style="color:#66CC00;font-weight:bold;">';
           
           $listing_text.= '&nbsp;' . $currencies->display_price($product_info['products_offer_low'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
            if($product_info['products_offer_high']>0){$listing_text.= '-&nbsp;' . $currencies->display_price($product_info['products_offer_high'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';}
            if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['products_offer_low'])/$product_info['products_msrp']*100);}


           $listing_text.='</span> <br/>From <b>';
           $listing_text.= ucwords(strtolower($product_info['products_manufacturer'])).'</b>' ;

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
		    while(strlen($curres)>1 && $rows<=6);
		    
		   
		if($index>0){ echo '<a href="/topic.php?health='.strtolower(urlencode($pname)).'" style="margin-left:5px;"><br style="clear:left;"/>See all similar products for '.$products_name.'</a>';}

}
}}
?>

<!-- similar_products_eof //-->
