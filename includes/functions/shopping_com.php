
    <?php
    function shopping_sidebar($upc)
    {
        global $shop_response;
        if(!is_numeric($upc)){return(0);}
        
        
                      if (! ($xmlparser = xml_parser_create()) )
                       {
                       echo ("Cannot create parser");
                       }
                    $shopping_com=file_get_contents('http://api.shopping.com/scripts/GSIsapiExt.dll/linkin_id-8034024/keyword-'.$upc);
                    
                    xml_set_element_handler($xmlparser, "shopOpenTag", "shopCloseTag");
                    xml_set_character_data_handler($xmlparser, "shopContents");
                    xml_parse($xmlparser, $shopping_com);
                    //echo $shopping_com;
                    
                    
    ?>
    
 
            <div style="padding-left: 5px; margin-bottom: 10px;text-align:center;margin-top:15px;">
                
                <?php echo $shop_response;?>
                <b>Powered by</b>
                <a rel="nofollow" href="http://www.shopping.com/?linkin_id=8034024"><img border="0" src="/images/shoppingcom.gif" alt="powered by shopping.com"/></a>
            </div>

                
      

<?php
}


function shopOpenTag($parser, $tagname, $attribs)
{
    global $current;
    global $xmlOut;
    global $product_info;
    global $withinOffer;
    global $shop_response;
   
    
    if($tagname=='STORE-OFFER')
    {
        $product_info['offerings']=$product_info['offerings']+1;
        $withinOffer=true;
        $shop_response.='<div style="border:solid 1px #3333CC;margin-top:20px;margin-left:5px;margin-right:5px;">
                <a href="{offer-url}" rel="nofollow" target="_blank"><img src="{image-url}" alt="{offer-name}" title="{offer-name}" border="0" style="margin:5px;width:100px;height:100px;float:left;"/></a>
                <b>{store-name}</b><br/><a href="{offer-url}" rel="nofollow" target="_blank">{offer-name}</a><br/><span style="color:#00CC00;font-weight:bold;">${offer-price}</span><br/>{stock-description}
                <br style="clear:both;"/></div>';
    }
    

    $current=$tagname;
    $xmlOut.= $tagname;

}

function shopCloseTag($parser, $tagname)
{
    global $xmlOut;
    global $shop_response;
    global $prod_url;
    global $withinOffer;

    $xmlOut.= '/'.$tagname.'<br/>';
    
    if($tagname=='STORE-OFFER')
    {
        $withinOffer=false;
    }
    if($tagname=='OFFER-URL' && $withinOffer)
    {
        $shop_response=str_replace('{offer-url}',$prod_url,$shop_response);
        $prod_url='';

    }

}

function shopContents($parser, $contents)
{
    global $current;
    global $xmlOut;
    global $product_info;
    global $updatePrice;
    global $withinOffer;
    global $shop_response;
    global $prod_url;
    if($current=='OFFER-DESCRIPTION')
    {
        //$product_info['products_description']=' '.$contents.' '.$product_info['products_description'];
    }
    if($current=='FULL-DESC')
    {
        if(strpos($contents,$product_info['products_description'])<1){
            $product_info['products_description']='<br/><br/>'.$contents.'.....<br/><br/>'.$product_info['products_description'];}
    }
    
    if($current=='IMAGE'){
        if(strlen($product_info['products_image'])<2)
        {
            $product_info['products_image']=$contents;
        }
      }
      
          if($current=='STOCK-DESCRIPTION'){
        
      }
      
    if($current=='OFFER-PRICE')
    {
        $contents=$contents*1.00+0.00;
        $product_info['products_offer_low']*1.00+0.00;
        $product_info['products_offer_high']*1.00+0.00;
    
        if($contents<$product_info['products_offer_low'] || $product_info['products_offer_low']==0.00){
            $product_info['products_offer_low']=$contents;
            $updatePrice=true;
        }
        elseif($contents>$product_info['products_offer_high'])
        {
            $product_info['products_offer_high']=$contents;
            $updatePrice=true;
        }
        
            
    }
    
    if($current=='OFFER-NAME' && $withinOffer)
    {
        $shop_response=str_replace('{offer-name}',$contents,$shop_response);
    }
    if($current=='OFFER-PRICE' && $withinOffer)
    {
        $shop_response=str_replace('{offer-price}',$contents,$shop_response);
    }
    if($current=='OFFER-URL' && $withinOffer)
    {
        $prod_url.= $contents;
    
           
    }
    if($current=='STOCK-DESCRIPTION' && $withinOffer)
    {
        $shop_response=str_replace('{stock-description}',$contents,$shop_response);
    }
    if($current=='STORE-NAME' && $withinOffer)
    {
        $shop_response=str_replace('{store-name}',$contents,$shop_response);
    }
    
    if($current=='IMAGE' && $withinOffer)
    {

        if(strpos($contents,'100x100')>0){
            $shop_response=str_replace('{image-url}',$contents,$shop_response);}
    }
      
      
    if($current=='RELATED-SEARCH-TERMS'){
          $xmlOut.= $contents;
      }
      
      $xmlOut.= '<p class="quote">'.$contents.'</p>';

}
?>
