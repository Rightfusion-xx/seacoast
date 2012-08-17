<?php
/*   
     include_once(DIR_WS_FUNCTIONS . '/search_tools.php');

?>
<!-- similar_products //-->
  <?php

  mini_prods();
    function mini_prods(){

        global $currencies;
        global $googlelist;
        global $results;
        global $page_links;

        global $products_name;
        global $alt_keywords;
        global $results;
        global $show_expanded_similar_products;
        $index=0;
        
        $googlelist='';
        $ptemp=$products_name . '(';
        $pname=trim(substr($ptemp,0,strpos($ptemp,'(')));

        if(count($results['products_id_similar'])<1)
        {
          $results['searchterm']=$pname;
          topic_search($results); 
        }
        else
        {
          $pname= $results['searchterm'];
        }
        

        // Check page_links var to see if other products were found.
          if($_REQUEST['products_id'])
          {
             $pl=explode(',',$page_links);
             foreach($pl as $item)
             {
               if($strpos=strpos($item,'?products_id='))
               {
                 $pid=(int)substr($item, $strpos+13);
                 if(!in_array($pid, $results['products_id_similar']))
                 {
                   array_push($results['products_id_similar'], $pid.'a');

                 }

               }
             }

          }


        if(count($results['products_id_similar'])>0)
        {


		?>
 <span class="buzz">Compare</span><br/> <?php
		    foreach($results['products_id_similar'] as $pid){
		    
                    if(strpos($pid,'a')==true)
                    {
                      $class='class="nolog"' ;
                      $pid=(int)$pid;
                    }
                    else
                    {
                      $class='';
                    }


		    if($pid!=$_REQUEST['products_id']){
		    if($product_info=tep_db_fetch_array(tep_db_query('SELECT products_image, p.products_id, p.products_msrp, pd.products_head_desc_tag as product_desc, p.products_price, products_name, manufacturers_name, m.manufacturers_id
		                            from products p join products_description pd on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id
		                            where p.products_id=\''.$pid.'\' and products_status=1'))){;
   $listing_text ='';
   $product_parts=parse_nameparts($product_info['products_name']);

   
 //Calculate membership discounts
    	$cm_price = tep_not_null($product_info['specials_new_products_price']) ? $product_info['specials_new_products_price'] : $product_info['products_price'];
  	  	$cm_price=calculate_member_price($cm_price,$product_info['manufacturers_id'],$product_info['products_name']);
      
      $rows++;

      $product_image_path='';
      
      if($rows<=20){

        $product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);

    }
      

  


      $listing_text.=!$show_expanded_similar_products ? '<div id="prod'.$rows.'" class="' : '';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.=!$show_expanded_similar_products ? 'mini-product_isspecial' : '';
      }else{
        $listing_text.=!$show_expanded_similar_products ? 'mini-product_regular' : '';
      }
      $listing_text.=!$show_expanded_similar_products ? '">' : '<p>';
      

            
            $listing_text.=  '<div style="float:left;" class="mini-listing-image">';
            if(strlen($product_image_path)>0){
             $listing_text.='<a '. $class . ' href= "' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '"><img alt="'.$product_info['products_name'].'" title="'.$product_info['products_name'].' '.$product_info['manufacturers_name'].'" src="'. $product_image_path . '" width="50" style="margin:5px;" ALIGN="left" border="0" /></a>';
            }
            $listing_text.='</div>';

            $listing_text.= '<h2 style="display:inline"><a '. $class . ' href= "' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '">' . $product_parts['name'] . '</a></h2>';
            
            $listing_text.=$show_expanded_similar_products ? '<br/>'.$product_info['product_desc'] : '';
            
            $listing_text.='<br/><span style="color:#66CC00;font-weight:bold;">';
            if (tep_not_null($product_info['specials_new_products_price'])) {

              $listing_text.= '&nbsp;<s>' .  $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$cm_price)/$product_info['products_msrp']*100);}
            } else {

              $listing_text.= '&nbsp;' . $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$cm_price)/$product_info['products_msrp']*100);}

            }

           $listing_text.='</span> '.$product_parts['attributes'].' from <b>';
           $listing_text.= $product_info['manufacturers_name'].'</b>' ;

            if($discountpct>0){
             $listing_text .= '<br/><span style="color:#ff0000;font-weight:bold;">'.$discountpct.'% Off</span>';
            }
   
           $listing_text.='<br style="clear:both;"/>';




      $listing_text.=!$show_expanded_similar_products ? '</div>' : '</p>';
      
      $googlelist.='<p style="width:600px;"><a href="/product_info.php?products_id='.$product_info['products_id'].'">'.$product_info['products_name'].'</a> &nbsp; 
              ' . $product_info['product_desc'] . '</p>';



    }		       }
    echo $listing_text;
    
    $listing_text='';
		        
          $index++;
    	  if($index==7 && 1==2)break;


		    }

		echo '<br style="clear:left;"/>';    
		if(is_array($alt_keywords))
		{
			foreach($alt_keywords as $temp_kw)
			{

                          // If there is a backlink from this destination link, go ahead and display it.
                                $link=link_exists('/topic.php?health='.strtolower(urlencode(trim($temp_kw))).',',$page_links);
                                if(strlen($link)>0)
                                {
				 echo '<h2 style="display:inline";><a href="'.$link.'" >'.ucwords(trim($temp_kw)).'</a>.</h2>&nbsp;&nbsp;';
                                }

  	}
		}}

}

*/
?>
<!-- similar_products_eof //-->
