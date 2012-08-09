<?php

function str_Ireplace($needle, $replacement, $haystack) {
   $i = 0;
   while (($pos = strpos(strtolower($haystack),
     strtolower($needle), $i)) !== false) {
       $haystack = substr($haystack, 0, $pos) . $replacement
           . substr($haystack, $pos + strlen($needle));
       $i = $pos + strlen($replacement);
   }
   return $haystack;
}
 
require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

if((int)$HTTP_GET_VARS['products_id']==CM_FTPID || (int)$HTTP_GET_VARS['products_id']==CM_PID){
	redir301('/community/');
}

$product_info_query = tep_db_query("select p.products_keywords, p.products_die, p.products_dieqty, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, pd.products_type, psu.product_sku,pd.products_departments,pd.products_ailments,pd.products_uses, p.products_weight, p.products_ordered, pd.products_head_keywords_tag, pd.products_viewed, date_format(p.products_date_added,'%m/%d/%Y') as products_date_added, p.products_last_modified, psu.product_upc, p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_msrp, p.products_price, p.products_tax_class_id, p.products_date_available, p.manufacturers_id, m.manufacturers_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd , ". TABLE_MANUFACTURERS ." m left outer JOIN products_sku_upc psu ON psu.product_ids = p.products_id where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . 
	"' and pd.products_id = p.products_id and m.manufacturers_id = p.manufacturers_id and pd.language_id =' " . (int)$languages_id . "'");


if(!($product_info = tep_db_fetch_array($product_info_query))){
    //No product found, redirect.
    redir301(HTTP_SERVER);
}
else
{
	$is_cm_eligible=strpos($product_info['products_name'],'*') ? 0 : 1;
    $tags_array['keywords']=$product_info['products_head_keywords_tag'];
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    //Get image location
    
    if (file_exists ('../images/'.'products/'.$product_info['products_id'].'.gif')){
        $product_image_path = '../images/'.'products/'.$product_info['products_id'].'.gif';}
    elseif (file_exists ('../images/'.'products/'.$product_info['products_id'].'.jpg')){
        $product_image_path = '../images/'.'products/'.$product_info['products_id'].'.jpg';}
    elseif (file_exists ('../images/'.'products/'.$product_info['products_id'].'.bmp')){
        $product_image_path = '../images/'.'products/'.$product_info['products_id'].'.bmp';}
    elseif (file_exists ('../images/'.'products/'.$product_info['products_id'].'.png')){
        $product_image_path = '../images/'.'products/'.$product_info['products_id'].'.png';}
    elseif  (tep_not_null($product_info['products_image'])){
        $product_image_path = '../images/'.$product_info['products_image']; }
    elseif ($product_info['manufacturers_id']=='69'){
             $product_image_path = '../images/'.'seacoast_logo.png';}
    
        
    //check for product specials
    $new_price = tep_get_products_special_price($product_info['products_id']);

    //Get product name
    $products_name = $product_info['products_name'];
    
    //Get price
    if ($new_price != '')
        { $price=($new_price);}
          else
          { $price=$product_info['products_price'];}
          
    //Calculate membership discounts
    if($product_info['manufacturers_id']==69)
    {
    	$cm_price=$price*.75; //25% Off
    }
    elseif(!strpos($product_info['products_name'],'*'))
    {
    	$cm_price=$price*.85; //15% Off 
    }
    else {
    	$cm_price=$price;
    }
          
    
    //Get review details
    $reviews_query = tep_db_query("select count(*) as count, avg(reviews_rating) as rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    $reviews_rating=ceil($reviews['rating']);
      
    //Check for alternate search keywords
    if(strlen($product_info['products_keywords'])>0)
    {
    	$alt_keywords=array();
    	$alt_keywords=split(',',$product_info['products_keywords']);  
    	 	                             
    }

}


?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $product_info['products_head_title_tag'] ?></title>
<meta name="description" content="<?php echo $product_info['products_head_desc_tag']; ?>" />
<meta name="keywords" content="<?php echo $product_info['products_head_keywords_tag']; ?>" />

<link rel="stylesheet" type="text/css" href="/stylesheet.css">
<script language="javascript"><!--
function popupWindow(url) {
window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require('includes/header_aw.php'); ?>

<div id="content">
              
            
        
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top">


<div style="text-align:left;margin-top:20px;margin-bottom:20px;">
                 <div class="green box" style="float:left;">
                	 <h3 style="font-size:16pt;margin-top:-5px;"><?php echo $products_name; ?></h3>
                	    
                	     <div id="supplement_image" style="float:left; margin-top:20px;margin-right:50px;margin-bottom:30px;">
					        <?php
					        if(isset ($product_image_path)) {?>
					        
					            <img src="/<?php echo $product_image_path;?>" id="prod_image" border="0"/>
					        
					        <script language="javascript"><!--
					            if(document.getElementById('prod_image').width>225){
					                document.getElementById('prod_image').width=225;
					            }
					           
					        //--></script>
					
					        <?php
					            }
					        ?>    
					    <?php 
		                 if(is_numeric($reviews_rating) && $reviews_rating>0){echo '<br/>'.draw_stars($reviews_rating);
		                }?>
					        <?php if($product_info['products_msrp']>0){?><br/><span style="text-decoration:line-through">Regular MSRP: $<?php echo number_format($product_info['products_msrp'],2)?></span><?php } ?>
					   
					   
					    </div>
        

                 
					    
					    
					    <div style="float:left;margin-left:2em;">
						<p style="text-align:left;">
						 <?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
			                
			                <b>Quantity:</b>
			                
			                <select name="qty">
			                <?php for($index=1;$index<=30;$index++)
			                {
			                    ?>
			                     <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
			                    <?php
			                
			                }?>
			                </select>
			                <br/>
			                 <?php if($product_info['products_die']){?>
                            
                             <span style="font-size:8pt;color:#FF0000;font-weight:bold;">Hurry! Only <?php echo $product_info['products_dieqty']?> left at this price.</span><br />
                             <?php } ?>
                             <input type="hidden" name="products_id" value="<?php echo $product_info['products_id'];?>"><br/>
			                                
                         	</p>
                 			<p style="text-align:left"><b>Buy Now</b><br/>
                 			<input type="submit" class="formbutton" id="button_price" value="<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>*" style="width:200px;height:30px;color:#66CC00;font-weight:bold;font-size:12pt;">
                 		</p>
                 		
						<?php if($is_cm_eligible){ ?>
						<?php if(!$_SESSION['cm_is_member']){ ?>
                 		<div style="border:dashed 1px #dddddd;padding:5px;white-space:nowrap;">
                 		<script type="text/javascript">
                 			function toggle_price(show_discount)
                 			{
                 				
                 				if(show_discount)
                 				{
                 					document.getElementById('button_price').value='<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>*';
                 					document.getElementById('button_price').style.color='#66CC00';
                 					document.getElementById('button_price').style.fontWeight='bold';
                 					document.getElementById('cm_price_disclaimer').style.display='block';
                  	                document.getElementById('extra_savings').style.display='none';
                 				}
                 				else
                 				{
                 					document.getElementById('button_price').value='<?php echo $currencies->display_price($price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>';
                 					document.getElementById('button_price').style.color='#666666';
                 					document.getElementById('button_price').style.fontWeight='normal';
                 					document.getElementById('cm_price_disclaimer').style.display='none';
                 	                document.getElementById('extra_savings').style.display='inline';
                 				}
                 				
                 			}
                 		</script>
                 		
                 		<input type="checkbox" name="cm_freetrial" value="true" checked onclick="toggle_price(this.checked);"/> <span style="color:ff0000;font-weight:bold;">SAVE INSTANTLY.<br/></span> Join Seacoast Vitamins-Direct FREE for 14-days 
                 		<span id="extra_savings" style="display:none;"><br/>and <span style="color:#ff0000;font-weight:bold;">save an extra <?php echo number_format(($price-$cm_price)/$price*100,0) ?>%</span></span>
                 		</div>
                 		</div>
                 		<br style="clear:both;"/>
                 		<div style="width:500px;">
                 		<h2 style="font-weight:bold;">You'll Get</h2>
                 		<ul>
                 		<li> <b><i>Additional 25%</i></b> off Seacoast Vitamins brand products </li>
                 		<li> <b><i>Additional 15%</i></b> off national brands</li>
                 		<li> Plus, 120% money-back guarantee, fast shipping, at-cost shipping prices, 25 years of nutrition experience, 3,000 products, secure ordering�just a few of the benefits of being a Seacoast Vitamins customer.</li>
                 		</ul>
                 		</div>
                 		<?php } ?>
                 		</form> 
                 		</p>
                 		
                 		<p id="cm_price_disclaimer"><i>* Seacoast Vitamins-Direct price shown.</i>
                 		<?php if(!$_SESSION['cm_is_member']){ ?><a href="/community/" target="_blank" rel="nofollow">Learn more.</a><?php } ?></p><?php } ?>               
                 		</div>
                 </div>       
                 
       
 </td><td valign="top">

               

                    <div style="text-align:center;" margin-top: 30px;padding-left:20px;padding-right:80px;padding-bottom:10px;white-space:nowrap;background:#ffffff;">
                        
		          	       <br /><b style="color:#666666">Risk Free</b><br/><div style="white-space:nowrap;"><img src="/images/quality.jpg" style="width:75px;"/><b style="color:#666666"><br/>120% Guarantee</b></div>   
                        
                        <img src="http://www.shopping.com/merchant_logo?ID=406477" width="130" border="0"alt="Great Store!" />
                         <br/>
 						
 					<!-- BEGIN: BizRate Medal (125x73 pixels) -->
					<img  style="margin:3em;" src="http://medals.bizrate.com/medals/dynamic/162489_medal.gif" alt="BizRate Customer Certified (GOLD) Site" width="125" height="73" align="top" border="0" >
					<!-- END: BizRate Medal (125x73 pixels) -->
                   
              </div>
                    
</td></tr></table>
<br style="clear:both"/>
  
  <div style="margin-top:3em;margin-bottom:3em;">
  	<?php include('includes/modules/similar_products_google_aw.php');?>
 	</div>

                     
                     <div style="float:right;margin:20px;width:250px;"><b class="spiffy">
  <b class="spiffy1"><b></b></b>
  <b class="spiffy2"><b></b></b>
  <b class="spiffy3"></b>
  <b class="spiffy4"></b>
  <b class="spiffy5"></b></b><div class="spiffyfg" style="padding:20px;">
    
                     <?php if(strlen($product_info['products_uses'])>0){?>

               <h2>Uses & Indications</h2>
            <p>
                <ul>
                    <?php 
                        $uses=split(',',str_replace(', ',',',str_replace('  ',' ',$product_info['products_uses'])));
                        $benefit_links='';
                        foreach($uses as $usename)
                        { ?>
                            <li><?php echo ucwords($usename)?></li>
                          <?php
                          
                          $benefits='<a href="/natural_uses.php?use='.urlencode(strtolower($usename)).'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$benefits;
                                                    
                        }
                    ?>
                </ul>
            </p><?php } ?>
            
            
                     <?php if(strlen($product_info['products_uses'])>0){
                     
                     ?>
               
               <h2 style="margin-top:40px;">Ailments & Concerns</h2>
            <p>
                <ul>
                    <?php 
                        $uses=split(',',str_replace(', ',',',str_replace('  ',' ',$product_info['products_ailments'])));
                        $benefit_links='';
                        foreach($uses as $usename)
                        { ?>
                            <li><?php echo ucwords($usename)?></li>
                          <?php
                          
                          $ailments='<a href="/ailments.php?remedy='.urlencode(strtolower($usename)).'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$ailments;
                                                    
                        }
                    ?>
                </ul>
            </p>

        <br style="clear:both;"/><?php } ?>     
  </div>


  <b class="spiffy">
  <b class="spiffy5"></b>
  <b class="spiffy4"></b>
  <b class="spiffy3"></b>
  <b class="spiffy2"><b></b></b>
  <b class="spiffy1"><b></b></b></b>
</div>
                 
            

        
        <a name="pd" id="pd"></a><h2 style="display:inline;">All About <?php echo $product_info['products_head_title_tag'];?></h2>

            <div id="prod_desc">
            <p> 
            
                <?php 
                	$tmp_desc=stripslashes($product_info['products_description']);
                	
                
                echo $tmp_desc; ?>
            </p>
            </div>
                             


                          

                       
                                                 

</div>


<?php require('includes/footer_aw.php'); ?>
<br>
<script language="JavaScript" src="http://chanalytics.merchantadvantage.com/inChannel/maq.js"></script>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

