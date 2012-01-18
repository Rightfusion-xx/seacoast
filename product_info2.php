<?php

require('includes/application_top.php');

// Seed random generator so that items are mixed up, but consistent across page refreshes.
srand($product_info['products_id']);

// check for moded url
//redirect_moded_url();

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

    $product_parts=parse_nameparts($product_info['products_name']);
    $tname=$product_parts['name'];
    $tmisc=$product_parts['attributes'];
    $shortname=$tname;

  //check URL
  //echo $_SERVER["REQUEST_URI"]."<br/>".str_replace('//','/',"/".seo_url_title($tname)."/".seo_url_title($product_info["manufacturers_name"])."/".seo_url_title($tmisc)."/p".$product_info['products_id']);exit();
  $test_url=(str_replace('//','/',"/".seo_url_title($tname)."/".seo_url_title($product_info["manufacturers_name"])."/".seo_url_title($tmisc)."/p".$product_info['products_id']));
  if($test_url<>$_SERVER["REQUEST_URI"])
  {
      redir301(str_replace('//','/',"/".seo_url_title($tname)."/".seo_url_title($product_info["manufacturers_name"])."/".seo_url_title($tmisc)."/p".$product_info['products_id']));
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

  include(DIR_WS_MODULES . 'customer_reviews.php');

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

$product_parts=parse_nameparts($product_info['products_name']);
$tname=$product_parts['name'];
$tmisc=$product_parts['attributes'];
$shortname=$tname;


$title=$product_info['products_head_title_tag'];//$tname . ' | '.$product_info['manufacturers_name'].' | '. $tmisc;
$description=$product_info['products_head_desc_tag']; //$shortname.' vitamin supplement from '.$product_info['manufacturers_name'] .' includes uses, indications and dosing information with '.$product_info['products_name'].'. '. $product_info['products_head_title_tag'];

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
              
               // remove comments
              $tmp_desc=preg_replace('/\<!--.*?--\>/im','',$tmp_desc);  
              
              
              
              //Count number of words in title / manufacturer
              $title_count=strlen($product_info['manufacturers_name']. ' '.$product_info['products_name'])+1;
              
                  if(preg_match('/(^(\<.*?\>))([^\<\>]{'.$title_count.'})/im',$tmp_desc,$matches,null,$pos))
                  {
                      //print_r($matches);exit();
                      // We have a valid description, and found the start, so go there.
                      $tmp_desc=substr($tmp_desc,strpos($tmp_desc,$matches[3]));
                      
                      //Check to see if there is a ">" tag before a "<" tag. If yes, start again from there,
                      //else use the found location
                   
            
                      //echo $matches[0];echo $tmp_desc;exit();
                      
                 
                  
              }
              
              
             
              
              
              
              if(strlen($product_info['products_target_keyword'])>0){$product_info['products_name']=$product_info['products_target_keyword'];}
              else{$product_info['products_target_keyword']=$tname;
                   $product_info['products_name']=$tname; }
              

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?> itemscope itemtype="http://schema.org/Product">

        <!-- Update your html tag to include the itemscope and itemtype attributes -->

<!-- Add the following three tags inside head -->
<meta itemprop="name" content="<?php echo $title; ?>">
<meta itemprop="description" content="<?php echo $description; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $title; ?></title>
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $product_info['products_head_keywords_tag']; ?>" />
<meta name="type" value="pp"  />

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

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<div class="container_16 clearfix" style="margin-top:2em;">
    <div class="grid_10">
        <?php 
            if(is_numeric($reviews_rating) && $reviews_rating>0){echo draw_stars($reviews_rating);
        }?>
        <h1 style="margin-top:0em;">
           <?php echo $title; ?>.
        </h1>
        
        
        <div style="margin:1em;" class="fb-like" data-href="<?php echo HTTP_SERVER.$test_url;?>" data-send="false" data-width="450" data-show-faces="true" data-action="recommend" data-font="tahoma"></div>

        
        <?php 
        if(strlen($product_info['products_takeaway'])>0)
        {
            echo '<p>',$product_info['products_takeaway'],'</p>'; 
        }

    if(strlen($first_review)>0)
        {
            echo '<p>',$first_review,'</p>';
        }
        ?>
        

        
        
        <?php
        
        $cache->addCache('products_main'.$pmod);
}

        ?>
        
        <div class="cool_box">
         <div id="supplement_image" style="margin-bottom:.5em;float:left;">
                    <?php
                    if(isset ($product_image_path) && file_exists($_SERVER['DOCUMENT_ROOT'].$product_image_path)) {
                        $dims=getimagesize($_SERVER['DOCUMENT_ROOT'].$product_image_path);
                        $width=$dims[0]>340 ? 340 : $dims[0];   ?>
                       <div id="actual_prod_image" style="background-color:#ffffff;">
                           <img src="<?php echo $product_image_path;?>" id="prod_image" border="0" alt="<?php echo str_replace('"','\'',$product_info['products_head_desc_tag']);?>." title="<?php echo $product_info['products_name'];?>" width="<?php echo $width;?>"/>
                       </div>

                    <?php } ?>
         </div>
            
            <div id="item_details" style="text-align:left;margin:1em;">

                <table cellpadding=0 cellspacing=0 border=0>
                    <tr>
                        <td>



                <b><?php echo $tmisc." ".$shortname; ?></b> from <b><?php echo $product_info['manufacturers_name'];?>.</b>


                
                <?php if($product_info['products_die'] && $product_info['products_dieqty']<1){?>
                    <p><?php echo $product_info['products_name'];?> is not available from Seacoast Vitamins at this time. Look to the right for recommended alternatives.</p>
                <?php }else{?> <span style="margin-left:1em";>  
                    <?php echo tep_draw_form('cart_quantity', tep_href_link('/shopping_cart.php', tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
                    <?php if(!$product_info['products_die'] && $new_price){?>
                                        <br/><span style="font-size:8pt;color:#FF0000;font-weight:bold;">Hurry! On sale while supplies last!</span><br/>
                                    <?php }?>
                    <br/><b>Quantity:</b>
                    <select name="qty">
                    <?php for($index=1;$index<=30;$index++){?>
                        <option value="<?php echo $index; ?>"><?php echo $index; ?></option>
                    <?php }?>
                    </select>
                    <br/>
                    
                    <?php if($product_info['products_die']){?>
                        <br/><span style="font-size:8pt;color:#FF0000;font-weight:bold;">Hurry! Only <?php echo $product_info['products_dieqty']?> left at this price.</span>
                    <?php } ?>
                    <input type="hidden" name="products_id" value="<?php echo $product_info['products_id'];?>">
                    <br/>
                    <div style="float:left;">
                        <div style="text-align:left">
                            <input type="submit" class="formbutton" id="button_price" value="<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?> - Direct Price*" style="display:inline;width:200px;height:30px;color:#66CC00;font-weight:bold;font-size:12pt;">
                        <!-- Place this tag where you want the +1 button to render -->
                                $GOOGLE_PLUS_ONE$


                        </div>
                        <?php if($is_cm_eligible){ ?>
                        <?php if(!$_SESSION['cm_is_member']){ ?>
                            <script type="text/javascript">
                            function toggle_price(show_discount){
                                if(show_discount){
                                    document.getElementById('button_price').value='<?php echo $currencies->display_price($cm_price, tep_get_tax_rate($product_info['products_tax_class_id'])); ?> - Direct Price*';
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
                            <br/><input type="checkbox" name="cm_freetrial" value="true" checked onclick="toggle_price(this.checked);"/>Yes! I want Direct-to-Member prices.<br/>My membership is FREE for 14-days.
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
                         <br/><br/><a href="#product_description_loc">Ingredients & Description</a></div>
                        <?php } ?>


                    </div>
                 <?php } ?>
                <br style="clear:both"/></span></form>

                    </td>
                    </tr>
                </table>
             </div>
            <br style="clear:both;"/>


        </div>
        
        
        <?php
if(!$cache->doCache('products_main2'.$pmod, true, $lastmod))
{
?>    


        <?php echo $review;?>


    <a name="product_description_loc"> </a><p><span class="buzz">Ingredients & Description</span></p>
<?php echo $tmp_desc;   ?>

           
           <?php if(strtotime($product_info['products_last_modified'])<strtotime('2007-03-01')||strlen($tmp_desc)<150)
                          { $show_expanded_similar_products=true;
                          include(DIR_WS_MODULES . 'similar_products_google.php');}?>
                          
           

        

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
                          
                          

        
        <?php
        // Related Health Links

    /*
        foreach($results['healthnotes'] as $item)
        {
            ?>
            <p><a href="<?php echo $item['url']?>"><?php echo $item['title'];?></a><br><?php echo $item['snippet'];?></p>
            
            <?php
        
        }
    */
?>
        
        
        
        
        <?php
        // pull up product categories from DB
    $cat_query=tep_db_query('SELECT cd.categories_id, cd.categories_name, cd.categories_htc_desc_tag from categories_description cd
                                    join products_to_categories p2c on p2c.categories_id=cd.categories_id
                                    where p2c.products_id='.(int)$_REQUEST['products_id'].' order by categories_name asc');
    
        ?>
        <br/><span class="buzz">From the following guides:</span>
        <br/>


     
     
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
        shuffle($categories);
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

      <?php  if(strlen($product_info['products_uses'])>0 || strlen($product_info['products_uses'])>0){


        ?>
                 <div style="padding:20px;border:1px solid #000000;background-color:#FFFFCC;">

                    <p><b>*Not intended to diagnose or treat diseases or ailments, and is not reviewed by the FDA.</b></p>

                     <span class="buzz">Uses & Indications.</span>

                     <ul>
                         <?php
                             $uses=preg_split('/,/',str_replace(', ',',',str_replace('  ',' ',$product_info['products_uses'])));
                             $benefit_links='';
                            shuffle($uses);
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

               <span class="buzz" style="margin-top:1em;">Ailments & Concerns.</span>

                <ul>
                    <?php
                        $uses=preg_split('/,/',str_replace(', ',',',str_replace('  ',' ',$product_info['products_ailments'])));
                        $benefit_links='';
                    shuffle($uses);
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

            echo 'Distributed or manufactured from ',$product_info['manufacturers_name'] , '. See more ';
              $mflink=link_exists('/index.php?manufacturers_id='.(int)$product_info['manufacturers_id'],$page_links);
              if(!strlen($mflink))
              {

                $mflink='/index.php?manufacturers_id='.(int)$product_info['manufacturers_id'];
              }
              echo '<a href="',$mflink,'">',$product_info['manufacturers_name'],'</a> products.';
            echo '<br />';
            ?>
            </p>        
     <?php if(strtotime($product_info['products_last_modified'])>strtotime('2007-03-01')||strlen($tmp_desc)<150)
                          {
                          include(DIR_WS_MODULES . 'similar_products_google.php');}?>
    

              

       
                 
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
    

    <!-- Place this render call where appropriate -->
    
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
    

    </div>
</div>
                     
   

                        


        
        
        
  
            

      
                        
        
        <?php
        $cache->addCache('products_main2'.$pmod);
        } //end cache 
            ?>   

       
        
        

 
    


<?php 
            if($seacoast_crawler)
            {
                echo '<div>',$product_info['products_id'],'</div><div>',HTTP_SERVER.$_SERVER['REQUEST_URI'],'</div>';
            }
        ?>


<?php $product_info['products_name']='';?>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
