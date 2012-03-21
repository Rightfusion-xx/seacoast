<?php

require(DIR_WS_INCLUDES . '/shopping_com_api.php');


$products_id=parse_section($url,'cheapest/','-');
$products_url=parse_section($url.'<eof>','-','<eof>');
$updatePrice=false;


$product_check_query = tep_db_query("select *, (select query from site_queries where param_id = '" . tep_db_input($_SERVER["REQUEST_URI"]) . "' group by query order by hits desc, time_created asc limit 0,1) as top_query from products_new p where p.products_status = '1' and p.products_id = '" . $products_id . "'");

if(!($product_info = tep_db_fetch_array($product_check_query))){
    //No product found, redirect.
    redir301(HTTP_SERVER);
}

if($haveupc=tep_db_fetch_array(tep_db_query('select products_id from products where products_upc="' . (int)$product_info['products_upc'] . '"'))){
    redir301('/product_info.php?products_id=' . $haveupc['products_id']);
 }

$products_name=$product_info['products_name'];


if($products_url!=urldecode(format_seo_url($products_name))){redir301('/cheapest/'.$products_id.'-'.format_seo_url($products_name));}

$product_info['manufacturers_name']=Ucwords(strtolower($product_info['products_manufacturer']));



                    @$shopping_com=file_get_contents(SHOPPING_COM_REST.'&attributeValue='.urlencode(str_replace(' ','',$product_info['products_upc'])).'&keyword='.urlencode(ereg_replace("[^A-Za-z0-9]", "", $product_info['products_name'])).'&showProductOffers=true');
                    
                   // echo SHOPPING_COM_REST.'&attributeValue='.urlencode($product_info['products_upc']).'&keyword='.urlencode($product_info['products_name']).'&showProductOffers=true'.'<br><br><br>';
                   // echo $shopping_com;
                    
                   // Create an XSLT processor
                    if(strlen($shopping_com)>1)
                    {
                   	$doc = new DOMDocument();
					$xsl = new XSLTProcessor();
					
					$doc->load($_SERVER['DOCUMENT_ROOT'].'/includes/xslt/shopping_com_offer_ads.xslt');
					$xsl->importStyleSheet($doc);
					
					@$doc->loadXML($shopping_com);

					
					$html= $xsl->transformToXML($doc);
					
					
					/*
					 $xsltproc = xslt_create(); 
					 
					 $arguments=array('/_xml'=>$shopping_com,
					 					'/_xsl'=>'file_get_contents()');
					 
					
					 // Perform the transformation 
					 $html = xslt_process($xsltproc, 'arg:/_xml','file://'.$_SERVER['DOCUMENT_ROOT'].'/includes/xslt/shopping_com_offer_ads.xslt', NULL, $arguments); 
					
					 // Detect errors 
					 if (!$html) echo('XSLT processing error: '.xslt_error($xsltproc)); 
					
					 // Destroy the XSLT processor 
					 xslt_free($xsltproc); 
					
					 // Output the resulting HTML 
					 //echo $html; 
					 
					 */
                    }
                    
                    if($updatePrice)
                    {
                        tep_db_query('UPDATE products_new SET products_offer_high="'.$product_info['products_offer_high'].'", products_offer_low="'.$product_info['products_offer_low'].'", products_head_desc_tag="'.tep_db_input($product_info['products_head_desc_tag']).'", products_image="'.tep_db_input($product_info['products_image']).'" WHERE products_upc="'.$product_info['products_upc'].'"');
                        
                    }
                    $product_info[products_description]=str_replace('. .','.',str_replace('..','.',str_replace('. .','.',$product_info[products_description])));
                    

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title>Discount Sale <?php echo $product_info['products_name']; ?> | <?php echo $product_info['manufacturers_name'];?></title>
<meta name="description" content="Compare discounts or free shipping on <?php echo $product_info['products_name'];?> and other supplements from <?php echo $product_info['manufacturers_name'];?>."/>
<meta name="keywords" content="<?php echo $product_info['products_name'];?>"/>


<link rel="stylesheet" type="text/css" href="/stylesheet.css">

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>


    <div id="content">

  

        <h1 class="h1_prod_head" style="padding-top:20px;" >
            Discount Sales on <?php echo $product_info['products_name']; ?>, <?php echo $product_info['manufacturers_name'];?>
        </h1>
        <p>
        	<b>We're sorry,</b> <?php echo $product_info['products_name']?> from <?php echo $product_info['manufacturers_name']?> is not in stock at Seacoast Vitamins. 
        	If you'd like to buy <?php echo $product_info['products_name']?> with your Seacoast Vitamins order, call 1-800-555-6792. Below are selected partners and alternative products.
        </p>
        <div style="width:45%;float:left;">
		 		<h2>Available from Partners</h2>
		                <?php echo $html;?>
		                <br style="clear:both"/>
		                <b>Powered by</b>
		                <a rel="nofollow" href="http://www.shopping.com/?linkin_id=8034024"><img border="0" src="/images/shoppingcom.gif" alt="powered by shopping.com"/></a>
		               
        </div>
        
        <div style="width:45%;float:left;">
        	
        	 <?php include(DIR_WS_MODULES . 'similar_products_google.php');?>	
        </div>

        <hr class="sectiondivider"/>
                          
                           <?php 
                           global $googlelist;
                           echo $googlelist;?>
        <hr class="sectiondivider"/>
                    <h2>Details</h2>
                    
                    <p>
                    UPC: <?php echo $product_info['products_upc']?><br/>
                    Offerings: <?php echo $product_info['offerings'];?></p>

                    <p><b><?php echo $product_info['products_name'];?> from <?php echo $product_info['manufacturers_name'];?> is available at $<?php echo $product_info['products_offer_low'];?>.</b> These low price deals on <?php echo $product_info['products_name'];?> are
                    offered through select authorized dealers of <?php echo $product_info['manufacturers_name'];?>. <?php echo $product_info['products_name'];?> nutritional supplements &amp; herbal remedies from <?php echo $product_info['manufacturers_name'];?> are guaranteed for satisfaction. Health &
                    wellbeing is available with discounted, cheapest <?php echo $product_info['products_name'];?>. Partner pricing ranges from $<?php echo $product_info['products_offer_low'];?> to $<?php echo $product_info['products_offer_high'];?>.</p>
        
        <hr class="sectiondivider"/>
        
        <a name="pd" id="pd"></a><h2 style="display:inline;">Partner Descriptions</h2><br/><b><?php echo $product_info['products_name'];?></b>
        <p>
            <?php echo $product_info['products_description'];?>
        </p>

        <hr class="sectiondivider"/>
                
                     <?php $tags_array['keywords']=$product_info['products_name'];?>
                     
                     <?php include(DIR_WS_MODULES . 'product_healthnotes.php');?>
                     
                             
                             <hr class="sectiondivider"/>
                             
                                   <?php include(DIR_WS_MODULES . 'similar_new_products_google.php');?>

                             <hr class="sectiondivider"/>


                           <?php include(DIR_WS_MODULES . 'similar_picks.php');?>
        <hr class="sectiondivider"/>
        
            <div name="nextlast" style="padding:20px;">
                <?php
                    $nextlast_query=tep_db_query('select * from products_new where products_id<'.$product_info['products_id'].' order by products_id desc limit 0,1');
                    if($nextlast=tep_db_fetch_array($nextlast_query))
                    {
                        echo '<div style="float:left;"><a href="/cheapest/'.$nextlast['products_id'].'-'.format_seo_url($nextlast['products_name']).'"><< '.$nextlast['products_name'].'</a></div>';
                    }
                    $nextlast_query=tep_db_query('select * from products_new where products_id>'.$product_info['products_id'].' order by products_id asc limit 0,1');
                    if($nextlast=tep_db_fetch_array($nextlast_query))
                    {
                        echo '<div style="float:right;"><a href="/cheapest/'.$nextlast['products_id'].'-'.format_seo_url($nextlast['products_name']).'">'.$nextlast['products_name'].' >></a></div>';
                    }
                ?>
                
            </div>
            
            

        <hr class="sectiondivider"/>
           <div id="render_alpha">
    <h2>New Products Index</h2>
        <a href="/cheapest/numeric/">0-10</a> | 
        <?php
            for($i=ord('A');$i<=ord('Z');$i++)
            {
                ?>
                    <a href="/cheapest/<?php echo strtolower(chr($i));?>/"><?php echo chr($i);?></a>
                <?php
                if($i<ord('Z')){echo '|';}
            }
        ?>
    </div>            
        <hr class="sectiondivider"/>
                     
                     
                     <?php //echo $xmlOut;?>
    </div>
                     
                     
                     </td></TR></TABLE>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>


<?php
function shopOpenTag($parser, $tagname, $attribs)
{
    global $current;
    global $xmlOut;
    global $product_info;
    global $withinOffer;
    global $shop_response;
   
    
    if($tagname=='offer')
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
    
    if($tagname=='offer')
    {
        $withinOffer=false;
    }
    if($tagname=='offerurl' && $withinOffer)
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
    if($current=='description')
    {
        $product_info['products_description']=' '.$contents.' '.$product_info['products_description'];
    }
    if($current=='fulldescription')
    {
        if(strpos($contents,$product_info['products_description'])<1){
            $product_info['products_description']='<br/><br/>'.$contents.'<br/><br/>'.$product_info['products_description'];}
            
            if(!strpos($contents,'$')){
                if($product_info['products_head_desc_tag']!=$contents)
                 $product_info['products_head_desc_tag']=substr($contents,0,2000);
                 $updatePrice=true;
                 }
    }
    
    if($current=='IMAGE'){
        if(strlen($product_info['products_image'])<2)
        {
            if($product_info['products_image']!=$contents){
                 $product_info['products_image']=$contents;
                 $updatePrice=true;
             }
        }
      }
      
          if($current=='stockstatus'){
          
        
      }
      
    if($current=='minprice' || $current=='maxprice')
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
    
    if($current=='name' && $withinOffer)
    {
        $shop_response=str_replace('{offer-name}',$contents,$shop_response);
    }
    if($current=='baseprice' && $withinOffer)
    {
        $shop_response=str_replace('{offer-price}',$contents,$shop_response);
    }
    if($current=='offerurl' && $withinOffer)
    {
        $prod_url.= $contents;
    
           
    }
    if($current=='stockstatus' && $withinOffer)
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
