<?php

require('includes/application_top.php');

// check for moded url
redirect_moded_url();

if(strpos($_SERVER['HTTP_USER_AGENT'],"seacoast-crawler")>0)
{
     $seacoast_crawler=true;
}    

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

if((int)$HTTP_GET_VARS['products_id']==CM_FTPID || (int)$HTTP_GET_VARS['products_id']==CM_PID){
	redir301('/community/');
}

$product_info_query = tep_db_query("select pd.products_target_keyword, p.products_keywords, p.products_die, p.products_sku, p.products_upc,                                         
						p.products_dieqty, pd.products_head_title_tag, pd.products_head_keywords_tag, 
						pd.products_head_desc_tag, pd.products_type,
						pd.products_departments,pd.products_ailments,pd.products_uses, 
						p.products_weight, p.products_ordered, pd.products_head_keywords_tag, 
						pd.products_viewed, date_format(p.products_date_added,'%m/%d/%Y') as 
						products_date_added, p.products_last_modified, 
						p.products_id, pd.products_name, pd.products_description, p.products_model, 
						p.products_quantity, p.products_image, pd.products_url, p.products_msrp,
						p.products_price, p.products_tax_class_id, p.products_date_available,
						p.manufacturers_id, m.manufacturers_name, pd.products_takeaway
						from " . TABLE_PRODUCTS . " p join  " . TABLE_PRODUCTS_DESCRIPTION . " pd on
						p.products_id=pd.products_id join ". TABLE_MANUFACTURERS ." m on m.manufacturers_id=p.manufacturers_id
						where p.products_status = '1' and p.products_id = '" . (int)$_REQUEST['products_id'] . 
	"' and pd.language_id =' " . (int)$languages_id . "'");


if(!($product_info = tep_db_fetch_array($product_info_query))){
    //No product found, redirect.
    redir301(HTTP_SERVER);
}
else
{
    //check URL
    if(seo_url_title($product_info['manufacturers_name'].' '.$product_info['products_name']." ".$product_info['products_id'])<>$url_title)
    {
        redir301($processor.seo_url_title($product_info['manufacturers_name'].' '.$product_info['products_name']." ".$product_info['products_id'],$pagenum));
    }
  
  if(strpos(' '.$_SERVER['HTTP_USER_AGENT'],'gsa-crawler')>0 )
  {
    include(DIR_WS_INCLUDES.'create_crawler_page.php');
    exit();
  }



    $lastmod=strtotime($product_info['products_last_modified']);
    $is_cm_eligible=strpos($product_info['products_name'],'*') ? 0 : 1;
    $tags_array['keywords']=$product_info['products_head_keywords_tag'];
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    //Get image location
 $product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);

        
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
    	$alt_keywords=preg_split('/,/',$product_info['products_keywords']);  
    	 	                             
    }

}


  // get all url links
  populate_backlinks();
  
  

$cache=new megacache(24*60*60);
if(!$cache->doCache('products_main'.$pmod, true, $lastmod))
{

    /*
if(strpos($product_info['products_name'],'(')>0)
{
    $title=trim(substr($product_info['products_name'],0,strpos($product_info['products_name'],'(')-1));   
    $shortname=$title;
}
else
{
    $title=$product_info['products_name'];
    $shortname=$title;
}
*/

$tname=preg_replace('/[^A-Za-z0-9-()|,\s\.]/i','',$product_info['products_name']);
$tname=preg_split('/([^a-z0-9-\s].+)/i',$tname,2,PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY );
$tmisc=$tname[1];
$tname=$tname[0];
$shortname=$tname;


$title=$tname . ' | '.$product_info['manufacturers_name'].' | '. $tmisc;
$description=$shortname.' vitamin supplement from '.$product_info['manufacturers_name'] .' includes uses, indications and dosing information with '.$product_info['products_name'].'. '. $product_info['products_head_title_tag']; 

// Create product Description
$tmp_desc=stripslashes($product_info['products_description']);
        $i=0;
        $tmp_len=strlen($tmp_desc);
        while($i<$tmp_len)
        {

              $i2=strpos($tmp_desc, '<a', $i);
              $i3=strpos($tmp_desc, '<img', $i);
              if($i3<$i2 && $i3)$i2=$i3;



                 $segment=substr($tmp_desc,$i,$i2);

                      if(is_array($alt_keywords))
               {
            foreach($alt_keywords as $tmp_keyword)
            {
                $tmp_keyword=trim($tmp_keyword);
                $segment=str_Ireplace($tmp_keyword,'<a href="/topic.php?health='.strtolower(urlencode(trim($tmp_keyword))).'" >'.ucwords(trim($tmp_keyword)).'</a>', $segment);
            }
               }
               $tmp.=$segment;

              if($i2)
              {

                $i=$i2;
                $i2=strpos($tmp_desc,'>',$i)+1;
                if(!$i2)$i2=$tmp_len-$i;
                $tmp.=substr($tmp_desc,$i,$i2);
                $i=$i2;

              }
              else
              {
                $i=$tmp_len;
              }

              }

              
              // remove errant widths
              $tmp_desc=preg_replace('/width="*.?"/im','',$tmp_desc);
              
              // remove empty tags , but not <td>
              $tmp_desc=preg_replace('/(^\<td)\<([a-z]+)[^\>]*?\>(\W|&nbsp;|\s)*\<\/\1\>/im','',$tmp_desc);  
              
              //echo $tmp_desc;exit();
              
              
              
              //Count number of words in title / manufacturer
              $title_count=str_word_count($product_info['manufacturers_name']. ' '.$product_info['products_name'])+1;
              
              if(preg_match('/([0-9a-z-.,]*\s){'.$title_count.'}/im',$tmp_desc,$matches))
              {
                  // We have a valid description, and found the start, so go there.
                  $tmp_desc=substr($tmp_desc,strpos($tmp_desc,$matches[0]));
                  
        
                  //echo $matches[0];echo $tmp_desc;exit();
                  
              }     
              
              if(strlen($product_info['products_target_keyword'])>0){$product_info['products_name']=$product_info['products_target_keyword'];}
              else{$product_info['products_target_keyword']=$tname;
                   $product_info['products_name']=$tname; }
              

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $product_info['products_head_keywords_tag']; ?>" />
<meta name="type" value="supplement"  /> 

<link rel="stylesheet" type="text/css" href="/stylesheet.css">
<link rel="stylesheet" type="text/css" href="/960_16_gs.css">

<!--[if IE]><style>
#prod_details {
	height: 0;
	he\ight: auto;
	zoom: 1;
}
</style><![endif]--> 

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div class="container_16 clearfix">
    <div class="grid_10">
        <?php 
            if(is_numeric($reviews_rating) && $reviews_rating>0){echo draw_stars($reviews_rating);
        }?>
        <h1 style="margin-top:0em;">
           <?php echo $tname; ?> 
        </h1>
        
        <?php 
        if(strlen($product_info['products_takeaway'])>0)
        {
            echo '<p>',$product_info['products_takeaway'],'</p>'; 
        }   
        ?>
        
        <b><?php echo $tmisc; ?></b> from <b><?php echo $product_info['manufacturers_name'];?>.</b>
        
        
        
        <?php echo $tmp_desc; ?>
        
        <div class="green box" style="margin:2em;">
             <span class="buzzbox">Seacoast Only Prices</span>
                <div id="supplement_image" style="float:left;margin-bottom:.5em;">
                    <?php
                    if(isset ($product_image_path) && file_exists($_SERVER['DOCUMENT_ROOT'].$product_image_path)) {?>
                       <div id="actual_prod_image"> 
                           <img width="100" src="<?php echo $product_image_path;?>" alt="<?php echo $product_info['products_name']?> on sale." title="Buy <?php echo $product_info['products_name']?>." id="prod_image" border="0" />
                       </div>
                      
                    <?php } ?>
                    <?php if($product_info['products_msrp']>0 && $product_info['products_msrp']>$product_info['products_price']){?>
                        <span style="text-decoration:line-through;">Regular MSRP: $<?php echo number_format($product_info['products_msrp'],2)?></span>
                    <?php } ?>
                </div>
                <?php if($product_info['products_die'] && $product_info['products_dieqty']<1){?>
                    <p><?php echo $product_info['products_name'];?> is not available from Seacoast Vitamins at this time. We recommend the following alternatives, below.</p>
                <?php }else{?> <span style="margin-left:1em";>  
                    <?php echo tep_draw_form('cart_quantity', tep_href_link('/shopping_cart.php', tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
                    <b>Quantity:</b>
                    <select name="qty">
                    <?php for($index=1;$index<=30;$index++){?>
                        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                    <?php }?>
                    </select>
                    <br/>
                    <?php if(!$product_info['products_die'] && $new_price){?>
                        <span style="font-size:8pt;color:#FF0000;font-weight:bold;">Hurry! On sale while supplies last!</span>
                    <?php }?>
                    <?php if($product_info['products_die']){?>
                        <span style="font-size:8pt;color:#FF0000;font-weight:bold;">Hurry! Only <?php echo $product_info['products_dieqty']?> left at this price.</span>
                    <?php } ?>
                    <input type="hidden" name="products_id" value="<?php echo $product_info['products_id'];?>">
                    <br/>
                    <div style="margin-left:1em;float:left;border:dashed 1px #dddddd;padding-left:1em;padding-right:1em;">
                        <div style="text-align:left"><b>Buy Now</b><br/>
                            <input type="submit" class="formbutton" id="button_price" value="<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>*" style="width:200px;height:30px;color:#66CC00;font-weight:bold;font-size:12pt;">
                        </div>
                        <?php if($is_cm_eligible){ ?>
                        <?php if(!$_SESSION['cm_is_member']){ ?>
                            <script type="text/javascript">
                            function toggle_price(show_discount){
                                if(show_discount){
                                    document.getElementById('button_price').value='<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>*';
                                    document.getElementById('button_price').style.color='#66CC00';
                                    document.getElementById('button_price').style.fontWeight='bold';
                                    document.getElementById('cm_price_disclaimer').style.display='block';
                                    document.getElementById('extra_savings').style.display='none';
                                }else{
                                    document.getElementById('button_price').value='<?php echo $currencies->display_price($price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?>';
                                    document.getElementById('button_price').style.color='#666666';
                                    document.getElementById('button_price').style.fontWeight='normal';
                                    document.getElementById('cm_price_disclaimer').style.display='none';
                                    document.getElementById('extra_savings').style.display='inline';
                                }
                            }
                            </script> 
                            <input type="checkbox" name="cm_freetrial" value="true" checked onclick="toggle_price(this.checked);"/> Yes! I want Direct-to-Member prices.<br/>My membership is FREE for 14-days.
                            <span id="extra_savings" style="display:none;"><br/><span style="color:#ff0000;font-weight:bold;">Save an extra <?php echo number_format(($price-$cm_price)/$price*100,0) ?>% plus
                            </span></span>
                            <br/>I'll get:
                            <ul style="font-weight:bold;margin-left:3em;">
                                <li>Member prices, cheap shipping</li>
                                <li>Side Effects Protection; <br/><i style="font-weight:normal;">Return opened product</i></li>
                            </ul>
                        <?php } ?>
                        
                        <div id="cm_price_disclaimer"><i>* Seacoast Vitamins-Direct price shown.</i><br/>
                        <?php if(!$_SESSION['cm_is_member']){ ?><a href="/community/" target="_blank" rel="nofollow">Learn more.</a><?php } ?>
                        </div>
                        <?php } ?> 
                    </div>
                 <?php } ?>  <br style="clear:both"/></span></form> 
                
             </div>      
        
        <?php include(DIR_WS_MODULES . 'customer_reviews.php');?>
        
           
           <?php if(strtotime($product_info['products_last_modified'])<strtotime('2007-03-01')||strlen($tmp_desc)<150)
                          { $show_expanded_similar_products=true;}?>
                          
           
             <p> <?php include(DIR_WS_MODULES . 'similar_products_google.php');?> </p>
        
        
                                  <?php if(strtotime($product_info['products_last_modified'])<strtotime('2007-03-01'))
                          { ?>
                          
                          
                                <?php
                                
                                ?>
                                <p>
                                You have reached <?php echo $product_info['products_name']?> on Seacoast.com from the manufacturer <?php echo $product_info['manufacturers_name']?>. We're proud to have 
                                served <?php echo $product_info['products_viewed']?> customers since <?php echo $product_info['products_date_added']?> who were also interested in purchasing <?php echo $product_info['products_name']?>.
                                It currently ranks as our <?php echo $product_info['products_ordered']?> most popular natural health product.
                                </p>
                                <p><b>Technical <?php echo $product_info['products_name']?> Details:</b> Locate this product using sku number <?php echo $product_info['product_sku']?> or ISBN <?php echo $product_info['product_upc']?>. For shipping, the weight is
                                equal to <?php echo $product_info['products_weight']?> pounds and is currently out of stock. Typical inquiries include
                                <?php
                                $keywords=preg_split('/,/',$product_info['products_head_keywords_tag']);
                                $keywords=array_reverse($keywords);
                                foreach($keywords as $keyword)
                                {
                                    echo $keyword.', ';
                                }
                                    
                             ?> and natural health. Seacoast Vitamins offers this product at a $<?php echo $product_info['products_msrp']-$product_info['products_price']?> discount
                             off of the suggested retail price $<?php echo $product_info['products_msrp']?>. Our price is $<?php echo $product_info['products_price']?>.</p>
                            
                          <?php }  ?> 
                          
                          
                          <?php $hubs=match_hub_links($page_links,  true);?>
  
    
             <?php              
             $mflink=link_exists('/catalog.php?page=',$page_links);
              if(strlen($mflink))
              {
                $catalog=automated_catalog::find(substr($mflink,strpos($mflink,'=')+1));
               
                echo '<p><a href="'.$mflink.'">Catalog of Nutritional Supplements '.$catalog->linktext.'</a></p>';
              }
              ?>
                          
                          
        <br><span class="buzz">Related Health Links for <?php echo $product_info['products_name'];?></span><br/>
        
        <?php
            
        foreach($results['healthnotes'] as $item)
        {
            ?>
            <p><a href="<?php echo $item['url']?>"><?php echo $item['title'];?></a><br><?php echo $item['snippet'];?></p>
            
            <?php
        
        }
?>
        
        
        
        
        <?php
        // pull up product categories from DB
    $cat_query=tep_db_query('SELECT cd.categories_id, cd.categories_name, cd.categories_htc_desc_tag from categories_description cd
                                    join products_to_categories p2c on p2c.categories_id=cd.categories_id
                                    where p2c.products_id='.(int)$_REQUEST['products_id'].' order by categories_name asc');
    
        ?>
        <br/><span class="buzz"><?php echo $product_info['manufacturers_name'], ' ',$product_info['products_name'], ' Health Guides';?></span>
        <br/>
        <p> Health Guides are simply categories in which <b><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></b>
        can be located, along with additional resources and related products.
       </p>

     
     
        <?php
        //Spool categories and intertwine with departments
        $categories=array();
        while($cat=tep_db_fetch_array($cat_query))
        {
            array_push($categories,$cat);
        }   
        foreach(explode(',',$product_info['products_departments']) as $item)
        {
            array_push($categories, array('categories_id'=>0, 'categories_name'=>ucwords(trim($item)), 'categories_htc_desc_tag'=>''));
        }
        
        $found=false;
        foreach($categories as $cat)
        {
            if($cat['categories_id']==0)
            {
                //Display a link to a department
                $mflink=link_exists('/departments.php?benefits='.urlencode(strtolower($cat['categories_name'])),$page_links) ? 
                                                                                     link_exists('/departments.php?benefits='.urlencode(strtolower($cat['categories_name'])),$page_links) :
                                                                                     '/departments.php?benefits='.urlencode(strtolower($cat['categories_name']));
            }
            else
            {
                //Display a link to a category page
                $mflink=link_exists('/index.php?cPath='.$cat['categories_id'],$page_links) ? 
                                                                                     link_exists('/index.php?cPath='.$cat['categories_id'],$page_links) :
                                                                                     '/index.php?cPath='.$cat['categories_id'];
            }
          
          if(!$found)
          {
            echo '<ul>';
          }
          $found=true; if(strlen($cat['categories_name'])>0){
          ?>
          <li><p><?php echo '<a href="',$mflink,'">',$cat['categories_name'],'</a>';
          if(strlen($cat['categories_htc_desc_tag'])>0){ echo ' - ',$cat['categories_htc_desc_tag']; }
          ?>
          </p> </li>

          <?php   }
          
          if(!$found)
          {    ?>
            <p>Sorry. No categories were found for this product.</p>
                      <?php
          }

        }
        
        if($found)
        {
          echo '</ul>';
        }
        
        ?>   
    </div>
    
    
    <div class="grid_6">
    
    <div id="supplement_image" style="margin-bottom:.5em;">
                    <?php
                    if(isset ($product_image_path) && file_exists($_SERVER['DOCUMENT_ROOT'].$product_image_path)) {
                        $dims=getimagesize($_SERVER['DOCUMENT_ROOT'].$product_image_path);
                        $width=$dims[0]>340 ? 340 : $dims[0];   ?>
                       <div id="actual_prod_image" style="background-color:#ffffff;"> 
                           <img src="<?php echo $product_image_path;?>" id="prod_image" border="0" alt="<?php echo str_replace('"','\'',$product_info['products_head_desc_tag']);?>." title="<?php echo $product_info['products_name'];?>" width="<?php echo $width;?>"/>
                       </div>
                       
                    <?php } ?>
    </div>
        
              
             <?php  if(strlen($product_info['products_uses'])>0 || strlen($product_info['products_uses'])>0){ ?>
                 <div style="padding:20px;border:1px solid #000000;background-color:#FFFFCC;">


                     <b><?php echo $product_info['products_head_title_tag']?>.</b><br/>
                     <span class="buzz"><?php echo $product_info['products_target_keyword'] ? $product_info['products_target_keyword'].' ' : '';?>Uses & Indications.</span>

                     <ul>
                         <?php 
                             $uses=preg_split('/,/',str_replace(', ',',',str_replace('  ',' ',$product_info['products_uses'])));
                             $benefit_links='';
                             foreach($uses as $usename)
                             { ?>
                             <li><?php echo ucwords($usename)?></li>
                             <?php
                                 if($mflink=link_exists('/natural_uses.php?use='.urlencode(strtolower($usename)),$page_links))
                                 {
                                     $benefits='<a href="'.$mflink.'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$benefits;
                                 }  

                             } 
                         ?>
                     </ul>

            
                      
            

                     <?php if(strlen($product_info['products_uses'])>0){
                     
                     ?>
               
               <span class="buzz" style="margin-top:1em;"><?php echo $product_info['products_target_keyword'] ? $product_info['products_target_keyword'].' ' : '';?>Ailments & Concerns.</span>
            
                <ul>
                    <?php
                        $uses=preg_split('/,/',str_replace(', ',',',str_replace('  ',' ',$product_info['products_ailments'])));
                        $benefit_links='';
                        foreach($uses as $usename)
                        { ?>
                            <li><?php echo ucwords($usename)?></li>
                          <?php
                          if($mflink=link_exists('/ailments.php?remedy='.urlencode(strtolower($usename)),$page_links))
                          {                           
                            $ailments='<a href="'.$mflink.'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$ailments;
                          }
                                                    
                        }
                    ?>
                </ul>
            
            

                <?php } ?>   
                      </div>
                 <?php } ?> 
                 
            <p>
                 <?php
            if (strlen($product_info['products_sku'])>0){
            echo 'SKU: '.$product_info['products_sku'] . '<br />'; }

            if (strlen($product_info['products_upc'])>0 ){
            echo 'UPC: '.$product_info['products_upc'] . '<br />'; 
            }

            echo 'Manufacturer Fresh from ',$product_info['manufacturers_name'] , '. View more ';
              $mflink=link_exists('/index.php?manufacturers_id='.(int)$product_info['manufacturers_id'],$page_links);
              if(!strlen($mflink))
              {
               
                $mflink='/index.php?manufacturers_id='.(int)$product_info['manufacturers_id'];
              }
              echo '<a href="',$mflink,'">',$product_info['manufacturers_name'],'</a> products.'; 
            echo '<br />';
            ?>
            </p>     
        <p style="font-style:italic;padding:1em;color:#36454f;">
            "<b>Buy <?php echo $product_info['products_name'];?> with confidence.</b> Seacoast Direct members
            can return unused and OPEN product from <?php echo $product_info['manufacturers_name'];?>.
            For fitness, vitality, weight loss,
            and whole-body health, chose potent nutrition with our affordable, discounted <?php echo $product_info['products_name'];?>. Your satisfaction is <b>Fully Guaranteed</b>."
        </p> 
                 
                 <div id="adsense_ad" style="margin-top:1em;">
    <script type="text/javascript"><!--
    google_ad_client = "ca-pub-6691107876972130";
    /* Product Page Right Side */
    google_ad_slot = "3365106085";
    google_ad_width = 336;
    google_ad_height = 280;
    //-->
    </script>
    <script type="text/javascript"
    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    </div>
    

    
    
 <?php // ailments, uses, etc  ?>

            <span class="buzz">Ailments & Uses for <?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></span>
            
            <p>
                Certain uses and indications are associated with
                <b><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></b>.
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
                     <?php
                 }
                 else
                 {
                  ?>
                   <p>
                      No ailments or uses are currently associated with 
                      <a href="/product_info.php?products_id=<?php echo $product_info['products_id'];?>"><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></a>.
                      To view a list of all cataloged ailments and uses, <a href="/ailments.php">click here</a>.

                   </p>
                  <?php

                 }
                 
                 
        // Uses

            ?>       

            <span class="buzz">Why Use <?php echo $product_info['products_name'],' from ',$product_info['manufacturers_name'] ;?>?</span>
            
            <p>
                This is a list of intended uses for
                <b><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></b>
                and is created by our editors and customers.
                This list may be partial and incomplete, and is not reviewed by the FDA, nor is the accuracy of the information guaranteed.
            </p>
            <?php

                 if(strlen($product_info['products_uses']))
                 {
                   $ailarr=explode(',',$product_info['products_uses']);
                   asort($ailarr);
                   foreach($ailarr as $item)
                   {
                    $item=trim($item);
                    $mflink=link_exists('/remedies/'.seo_url_title($item),$page_links) ;
                    $mflink=strlen($mflink) ? $mflink : '/remedies/'.seo_url_title($item);

                    ?>
                    <p><a href="<?php echo $mflink;?>"><?php echo $item?></a></p>

                                      <?php



                    }
                    
                    ?>

                    <?php

                 }
                 else
                 {
                  ?>
                   <p>
                      No uses are currently associated with 
                      <b><?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?></b>.
                      To view a list of all cataloged uses, <a href="/symptoms/">click here</a>.

                   </p>
                  <?php

                 }

            ?>

        <span class="buzz"><?php echo 'Popular topics that reference ' , $product_info['products_name'] , ' from ' , $product_info['manufacturers_name']?> </span>
      
      <p> These are the most popular keyword searches used to locate
       <?php echo $product_info['manufacturers_name'], ' ', $product_info['products_name'];?>.
       Click any link below for additional information and related health topics.
     </p>

      <?php include(DIR_WS_MODULES . 'similar_picks.php');?>
      
      <?php echo $searches_string;?>       
    
     
    </div>
</div>
                     
   

                        


        
        
        
  
            

      
                        
        
        <?php
        $cache->addCache('products_main'.$pmod);
        } //end cache 
            ?>   

       
        
        

 
    


<?php 
            if($seacoast_crawler)
            {
                echo '<div>',$product_info['products_id'],'</div><div>',HTTP_SERVER.$_SERVER['REQUEST_URI'],'</div>';
            }
        ?>



<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

