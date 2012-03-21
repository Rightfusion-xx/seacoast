<?php
$layerrows=5000;
$rendered_upsell=true;

function renderRegularProdEx($product_info,$rows='')
{
    global $currencies, $layerrows, $rendered_upsell, $cart;
    
    $listing_text='';
    $product_parts=parse_nameparts($product_info['products_name']);
    $link=(str_replace('//','/',"/".seo_url_title($product_parts['name'])."/".seo_url_title($product_info["manufacturers_name"])."/".seo_url_title($product_parts['attributes'])."/p".$product_info['products_id']));
    
 

      $product_image_path='';
      
      if($rows<=20){

        $product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);

      }
      
    //Calculate membership discounts
    $cm_price = tep_not_null($product_info['specials_new_products_price']) ? $product_info['specials_new_products_price'] : $product_info['products_price'];
    $cm_price=calculate_member_price($cm_price,$product_info['manufacturers_id'],$product_info['products_name']);
  	
	

      $listing_text.='<div id="sresults"><div id="prod'.$rows.'" class="';
      
      $listing_text.='">';

            $listing_text.= '<h2 style="line-height:1em;"><a href="' . $link . '" style="font-size:12pt;">'. $product_parts['name'] . '</a></h2>';
            $listing_text.= '' . $product_parts['attributes'] . ', ' . $product_info['manufacturers_name'];
            if(strlen($product_image_path)>0){
             $listing_text.='<div style="float:left;margin-right:1em;margin-top:1em;">'; 
             $listing_text.='<a href="' . $link . '"><img border="0" src="'. $product_image_path . '" style="width:70px;" alt="'.$product_info['products_name'].' '.$product_info['manufacturers_name'].'" /></a>';
             $listing_text.='</div>';
            }
            
            $listing_text.='<br/>';
            


            if($product_info['products_isspecial']=='1')
            {
              $listing_text.='<span style="color:#FF0000;font-weight:bold;font-style:italic;">Top Pick</span>';
            }

            $listing_text.='<br/><span style="color:#66CC00;font-weight:bold;display:inline;" name="cm_price">';
            if (tep_not_null($product_info['specials_new_products_price'])) {

              $listing_text.= '&nbsp;<s>' .  $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$cm_price)/$product_info['products_msrp']*100);}
            } else {

              $listing_text.= 'From ' . $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$cm_price)/$product_info['products_msrp']*100);}

            }
            $listing_text.='</span><span style="color:#ff0000;font-weight:bold;display:inline;" name="cm_discountpct">';
            if($discountpct>0 && $discountpct<100){
             $listing_text .= '<br/>'.$discountpct.'% Off';
            }
            $listing_text.='</span>';
            
                        
			$listing_text.='<span style="color:#333333;font-weight:bold;display:none;" name="regular_price">';
            if (tep_not_null($product_info['specials_new_products_price'])) {

              $listing_text.= '&nbsp;<s>' .  $currencies->display_price($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['specials_new_products_price'])/$product_info['products_msrp']*100);}
            } else {
              $listing_text.= '&nbsp;' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['products_price'])/$product_info['products_msrp']*100);}

            }

           $listing_text.='</span><span style="color:#ff0000;font-weight:bold;display:none;" name="regular_discountpct">';
                       
           
           if($discountpct>0){
             $listing_text .= '<br/>'.$discountpct.'% Off';
            }
           
           $listing_text.='</span>';


                                        
           if(strlen($product_info['products_head_desc_tag'])>0){$listing_text.='<br /><br />'.$product_info['products_head_desc_tag'];}



  
      $listing_text.='</div></div>';                     
      $layerrows=$layerrows-1;
      return($listing_text);



}





function renderRegularProd($product_info,$rows='')
{
    global $currencies, $layerrows, $rendered_upsell, $cart;
    
    $listing_text='';
    $product_parts=parse_nameparts($product_info['products_name']);
    
    if(!$cart->in_cart(CM_FTPID) && !$cart->in_cart(CM_PID) && !$_SESSION['cm_is_member']){
    if(!$rendered_upsell)
        {
            $listing_text.=RenderUpsell($listing_text);
                $rendered_upsell=true;
            }
    }
    
      

      $product_image_path='';
      
      if($rows<=25){

        $product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);

      }
      
    //Calculate membership discounts
    $cm_price = tep_not_null($product_info['specials_new_products_price']) ? $product_info['specials_new_products_price'] : $product_info['products_price'];
    $cm_price=calculate_member_price($cm_price,$product_info['manufacturers_id'],$product_info['products_name']);
      
    

      $listing_text.='<div id="sresults"><div id="prod'.$rows.'" class="';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.='product_isspecial';
      }else{
        $listing_text.='product_regular';
      }
      $listing_text.='">';

            $listing_text.= '&nbsp;<h2 style="display:inline;"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '" style="font-size:12pt;">'. $product_parts['name'] . '</a></h2>';
            $listing_text.= '<br/>' . $product_parts['attributes'] . ', ' . $product_info['manufacturers_name'];
            if(strlen($product_image_path)>0){
             $listing_text.='<div class="listing-image">'; 
             $listing_text.='<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']) . '"><img border="0" src="'. $product_image_path . '" style="margin:5px;width:70px;overflow:hidden;" ALIGN="left" alt="'.$product_info['products_name'].' '.$product_info['manufacturers_name'].'" /></a>';
             $listing_text.='</div>';
            }
            
            $listing_text.='<br/>';
            


            if($product_info['products_isspecial']=='1')
            {
              $listing_text.='&nbsp;-&nbsp;<span style="color:#FF0000;font-weight:bold;font-style:italic;">Top Pick</span>';
            }

            $listing_text.='<br/><span style="color:#66CC00;font-weight:bold;display:inline;" name="cm_price">';
            if (tep_not_null($product_info['specials_new_products_price'])) {

              $listing_text.= '&nbsp;<s>' .  $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$cm_price)/$product_info['products_msrp']*100);}
            } else {

              $listing_text.= 'From ' . $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$cm_price)/$product_info['products_msrp']*100);}

            }
            $listing_text.='</span><span style="color:#ff0000;font-weight:bold;display:inline;" name="cm_discountpct">';
            if($discountpct>0 && $discountpct<100){
             $listing_text .= '<br/>'.$discountpct.'% Off';
            }
            $listing_text.='</span>';
            
                        
            $listing_text.='<span style="color:#333333;font-weight:bold;display:none;" name="regular_price">';
            if (tep_not_null($product_info['specials_new_products_price'])) {

              $listing_text.= '&nbsp;<s>' .  $currencies->display_price($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['specials_new_products_price'])/$product_info['products_msrp']*100);}
            } else {
              $listing_text.= '&nbsp;' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
              if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['products_price'])/$product_info['products_msrp']*100);}

            }

           $listing_text.='</span><span style="color:#ff0000;font-weight:bold;display:none;" name="regular_discountpct">';
                       
           
           if($discountpct>0){
             $listing_text .= '<br/>'.$discountpct.'% Off';
            }
           
           $listing_text.='</span><br/><b>';
           $listing_text.= $product_info['manufacturers_name'].'</b>' ;


                                        
           if(strlen($product_info['product_desc'])>0){$listing_text.='<div class="product_listing" onmouseover="this.style.height=\'auto\';this.style.overflow=\'visible\';this.style.backgroundColor=\'#ffffcc\';" onmouseout="this.style.overflow=\'hidden\';this.style.backgroundColor=\'#ffffff\';this.style.height=\'7em\';">' . $product_info['product_desc'] . '</div>';}



  
      $listing_text.='</div></div>';                     
      $layerrows=$layerrows-1;
      return(getHubKeywordsAndRewriteContent($listing_text));




}




function renderComparisonProd($product_info,$rows='')
{
      global $currencies;
      $listing_text='';


      $listing_text.='<p><div id="prod'.$rows.'" class="';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.='product_isspecial';
      }else{
        $listing_text.='product_regular';
      }
      $listing_text.='">';
            
            $listing_text.='<div class="listing-image">';
            if(strlen($product_info['products_image'])>0){
             $listing_text.='<img src="'. $product_info['products_image'] . '" width="50" style="margin:5px;" ALIGN="left" />';
            }
            $listing_text.='</div>';

            $listing_text.= '&nbsp;<a href="/cheapest/' . $product_info['products_id'] . '-'.format_seo_url($product_info['products_name']).'"><b>' . $product_info['products_name'] . '</b></a>';
            if($product_info['products_isspecial']=='1')
            {
              $listing_text.='&nbsp;-&nbsp;<span style="color:#FF0000;font-weight:bold;font-style:italic;">Top Pick</span>';
            }

            $listing_text.='<br/><span style="color:#66CC00;font-weight:bold;">';


            $listing_text.= '&nbsp;' . $currencies->display_price($product_info['products_offer_low'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';
            if($product_info['products_offer_high']>0){$listing_text.= '-&nbsp;' . $currencies->display_price($product_info['products_Offer_high'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '&nbsp;';}
            if($product_info['products_msrp']>0){$discountpct=(int)(($product_info['products_msrp']-$product_info['products_offer_low'])/$product_info['products_msrp']*100);}


           $listing_text.='</span><b>';
           $listing_text.= ucwords(strtolower($product_info['products_manufacturer'])).'</b>' ;

            if($discountpct>0){
             $listing_text .= '<br/><span style="color:#ff0000;font-weight:bold;">Up to '.$discountpct.'% Off</span>';
            }
   
           $listing_text.='<br style="clear:left;"/>';
            if(strlen($product_info['products_head_desc_tag'])>0){$listing_text.='<div style="clear:both;border:dashed 1px #cccccc;margin:3px;">' . substr($product_info['products_head_desc_tag'],0,240) . '...</div>';}



  
      $listing_text.='</div></p>';
      return($listing_text);

}



function RenderUpsell($listing_text)
{

$listing_text.='

<div class="product_regular" style="border:1px solid #ffffff;overflow:visible;margin-bottom:0px;">

	
	<div style="z-index:6;overflow:hidden;">
		<div style="margin-right:3em;margin-left:15px;">
		<span style="color:#ff0000;font-size:18pt;font-weight:bold;">YES!</span>
		<p>
		<input type="checkbox" value="true" id="cm_freetrial" checked onclick="toggleprices();"> I want to try Seacoast Vitamins-Direct <b>FREE</b> for 14-Days
		and receive <b>member only pricing</b>!
		</p>
		<p>
			<a href="/community/">Learn more...</a>
		</p>
		<p id="cm_pricedisclaimer" style="display:block;">
		<i>*Seacoast Vitamins-Direct prices shown.</i>
		</p>
		<p id="cm_pricediscount" style="display:none;color:#ff0000;font-weight:bold">
			Save 15% to 25% extra!
		</p>
		<p>
			Already a member? <a href="login.php">Log in</a>
		</p>
		</div>
	
	
	</div>
	<img src="/images/upsell.png" width=1 height=1 style="position:absolute;top:0;left:0;width:22.5em;height:21em;z-index:-1;"/>
</div>

<script type="text/javascript">	

function getElementsByName_iefix(tag, name) {
     
     var elem = document.getElementsByTagName(tag);
     var arr = new Array();
     for(i = 0,iarr = 0; i < elem.length; i++) {
          att = elem[i].getAttribute("name");
          if(att == name) {
               arr[iarr] = elem[i];
               iarr++;
          }
     }
     return arr;
}

	
function toggleprices()
	{
		var i;
		x=getElementsByName_iefix(\'span\',\'cm_price\');
		y=getElementsByName_iefix(\'span\',\'regular_price\');
		z=getElementsByName_iefix(\'input\',\'cm_freetrial\');
		q=getElementsByName_iefix(\'span\',\'cm_discountpct\');
		r=getElementsByName_iefix(\'span\',\'regular_discountpct\');
		for(i=0;i<x.length;i++)
		{
			x[i].style.display=x[i].style.display==\'inline\' ? \'none\' : \'inline\' ;
			y[i].style.display=y[i].style.display==\'inline\' ? \'none\' : \'inline\' ;
			z[i].value=z[i].value==\'true\' ? \'\' : \'true\' ;
			r[i].style.display=r[i].style.display==\'inline\' ? \'none\' : \'inline\' ;
			q[i].style.display=q[i].style.display==\'inline\' ? \'none\' : \'inline\' ;
		}
		
		document.getElementById(\'cm_pricedisclaimer\').style.display=document.getElementById(\'cm_pricedisclaimer\').style.display==\'block\' ? \'none\' : \'block\' ;
		document.getElementById(\'cm_pricediscount\').style.display=document.getElementById(\'cm_pricediscount\').style.display==\'block\' ? \'none\' : \'block\' ;
		

	}
	
</script>  ';
return ($listing_text);
}

?>