<?php


  require('includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
$cache=new megacache(60*60*24*2); //2 day timeout

if(!$cache->doCache('hn'))
{
    

// check for moded url
redirect_moded_url();    

$ContentID=$_REQUEST['article'];
$subcat=$_REQUEST['subcat'];

if($_REQUEST['subcat'])
{
    
    $url_title=str_replace('-'.$_REQUEST['subcat'],'',$url_title);

}


if($ContentID)
{

    try
    {
        $current_article=healthnotes::find($ContentID);
        
            
    }
    catch(exception $e)
    {
        
        redir301('/');
    }
}
else
{
    try 
    {
        $current_article=healthnotes::find_by_resourcepath(urldecode($_SERVER['QUERY_STRING']));
    }
    catch(exception $e)
    {
        redir301('/');
        
    }
}

if(seo_url_title($current_article->title.'-'.$current_article->contentid)!=$url_title)
{
    redir301('/health-guide/'.seo_url_title($current_article->title.'-'.$current_article->contentid));    
}

if($_REQUEST['subcat'])
{
    $current_article->resourcepath=str_ireplace('~default',$_REQUEST['subcat'],$current_article->resourcepath);
    $current_article->title.=' ' . ucwords(str_replace('-',' ',$_REQUEST['subcat']));
    
    
}
 



try
{
    $url = HEALTHNOTES_URI.$current_article->resourcepath."?apikey=".HEALTHNOTES_KEY."&format=html&links=resource-path-encoded&request_handler_uri=/health_library.php&styles=basic";
    @$hn=file_get_contents($url);   
}
catch(exception $e)   
{
    redir301(HTTP_SERVER);
}

if(!$hn)
{
    redir301(HTTP_SERVER);
}
/*if(!isset($_GET['article'])){
    if(isset($_GET['page']))
    {
     $url=$url."&page={$_GET['page']}";
    }
    else
    {
     $url=$url.'&page=ltdus.cfm';
    }
    $hn=file_get_contents($url);
    
    $pageTitle='Natural Health Library & Encyclopedia';
    $metaDescription='Detailed & comprehensive resources for natural living and a healthy lifestyle.';
    $metaKeywords='health library, health encyclopedia, health resources, natural health, alternative health, nutrition';
}
*/
                 
    
    
    $hn=iconv('UTF-8','ASCII//TRANSLIT',$hn);
    
    $hn=substr($hn,0,strpos($hn,'hnie_CopyrightDisclaimerText')-12) ;
    $hn=substr($hn,0,strpos($hn,'_hni_gapt_div')-9);
    $hn=preg_replace('/\<h2 .*?\>(.+?)\<\/h2\>/i', '',$hn);
    
     //echo $hn; exit();    
    
    //$hn=preg_replace('/\<div class="hnie_CopyrightDisclaimerText"\>(.*)\<\/script\>/ims','',$hn); 
    //$hn=substr($hn,0,strpos($hn,'<div class="hnie_CopyrightDisclaimerText">'));
    //$hn.='</div></content></assetContentView>';
    //$hn=preg_replace('/\<h2(.*?)\<\/h2\>/ims','',$hn);
       




      
      
      
      /*foreach($xml->stylesheets as $item)
      {
          $stylesheets.=$item->asXML();
      }
      
      foreach($xml->clientScripts as $item)
      {
          $scripts.=$item->asXML();
      }
      
      $xml=$xml->content;
      
      foreach($xml as $item)
      {
          foreach($item->attributes() as $a=>$b)
          {
              if(($a=='class' && $b=='hnie_CopyrightDisclaimerText') || $b=='hnise_Title' || $b=='hni_LogoPlaceholder' || (strpos($item->asXML(),'google-analytics.com')))
              {
                  $gonext=true;
              }
              else
              {
                  $gonext=false;
              }
          }
          
          
          if($gonext)
          {
              continue;
          }
          else
          {
              $page_content.=$item->asXML();
              
          }
      }   */
      

    $pageTitle=$current_article->title;
    $topic=$pageTitle;
    $searchterm=$topic;


    switch($current_article->assettype)
    {
        case 'Advertorial':
            $pageTitle=$pageTitle . ' | Natural Health Advice';
            break;
        case 'A-Z Index':
            $pageTitle=$pageTitle . ' | Complete Natural Health A-Z Index';
            break;
        case 'Button Callout':
            $pageTitle=$pageTitle . ' | Natural Health Smarts';
            break;
        case 'Diet':
            $pageTitle=$pageTitle . ' | Natural Dieting';
            break;
        case 'Drug':
            $pageTitle=$pageTitle . ' | Side Effects and Interactions';
            break;
        case 'Feature':
            $pageTitle=$pageTitle . ' | Featured';
            break;
        case 'Food Guide':
            $pageTitle=$pageTitle . ' | Eating Natural and Healthy';
            break;
        case 'General Callout':
            $pageTitle=$pageTitle . ' | Take a Look';
            break;
        case 'Generic':
            $pageTitle=$pageTitle . ' | Natural Health';
            break;
        case 'Health Condition':
            $pageTitle=$pageTitle . ' | Natural Remedies & Cures';
            break;
        case 'Homeopathic Remedy':
            $pageTitle=$pageTitle . ' | Homeopathic Remedy';
            break;
        case 'Homeopathy':
            $pageTitle=$pageTitle . ' | What is Homeopathy?';
            break;
        case 'Nutritional Supplement':
            $pageTitle=$pageTitle . ' | Dietary Supplement';
            break;
        default:
            $pageTitle=$pageTitle . ' | Natural Health Research';
            break;
    }

    
    $metaDescription=$current_article->title . ' ' . $current_article->assettype . ' information for a naturally healthy life. Learn more about ' . $current_article->title . '. Get reviews, opinions, side effects and more on ' .$current_article->title;
    
                     
    
//Do Google Searches
if(GOOGLE_MINI_SERVING)
{
if($_REQUEST['page']==''){
$gquery=GOOGLE_SEARCH_URL . "num=10&filter=0&as_q=Health+Encyclopedia&q=" . urlencode($searchterm). '+-health_library';
$gcategories=file_get_contents($gquery);}

$gquery=GOOGLE_SEARCH_URL . "num=20&filter=0&q=inurl%3Aproduct_info+" . urlencode($searchterm);
if($_REQUEST['page']!=''){$gquery.='&start='.(($_REQUEST['page']*20)+1); }
$products=file_get_contents($gquery);

}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
<meta name="description" content="<?php echo $metaDescription;?>">
<meta name="keywords" content="<?php echo $metaKeywords;?>">
<title><?php echo $pageTitle; ?></title>


<link rel="stylesheet" type="text/css" href="/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top" rowspan="2">
	  <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
      </table>
    </td>
    <td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->

    <td width="100%" valign="top"> 
    
<div id="content">

    <h1><?php echo $pageTitle; ?></h1>
                      <?php
                    
            //Display Categories
            if(parse_section($gcategories, '<M>','</M>')>=1)
		        {
		        ?>
		            <h2>
		            Related <?php echo $searchterm; ?> Products</h2>
		            <p>
		          <?php
		            //Show all the categories
		            $index=1;
        		    
		            $sdelim='<R N="' . $index . '">';
		            $curres=parse_section($gcategories, $sdelim, '</R>');
        		    
		            echo ('<ul style="display:inline;width:610px;clear:both;">');		    

		            do{
        		        
		                echo '<li style="width:300px;float:left;margin-left:10px;"><a href="' . urldecode(parse_section($curres, '<UE>', '</UE>')) . '">'. html_entity_decode(parse_section($curres, '<T>', '</T>')) . '</a></li>';
        		        
		                $index++;
            		    
		                $sdelim='<R N="' . $index . '">';
		                $curres=parse_section($gcategories, $sdelim, '</R>');
            		    
		            }
		            while(strlen($curres)>1);
        		    
		            echo '</ul><br style="clear:both;"/></p>';
        		
		        }		  
                
                echo '<br/>'; 
                echo $hn;

    
		
		$total_prods=(int)parse_section($products, '<M>','</M>');
		if($total_prods>=1)
		{
		?>
		    <h2>
		    Related <?php echo $searchterm; ?> Products</h2>
		    <p>
		  <?php
		    //Show all the products
		    $index=1;
		    if($_REQUEST['page']!=''){$index=(($_REQUEST['page']*20)+1); }
		    
		    $sdelim='<R N="' . $index . '">';
		    $curres=parse_section($products, $sdelim, '</R>');
		    

		    do{
		        
		        $pid=parse_section($curres, 'http://www.seacoastvitamins.com/product_info.php?products_id=','</U>');
		        
		        if($product_info=tep_db_fetch_array(tep_db_query('SELECT products_image, p.products_id, p.products_msrp, pd.products_head_desc_tag as product_desc, p.products_price, products_name, manufacturers_name, m.manufacturers_id
		                            from products p join products_description pd on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id
		                            where p.products_id=\''.$pid.'\' and products_status=1'))){;
   $rows=1;
   $listing_text='';
   
   
      
      $rows++;

      $product_image_path='';
      
      if($rows<=20){

$product_image_path=select_image($product_info['products_id'], $product_info['products_image'],  $product_info['manufacturers_id']);

        
}
  


      $listing_text.='<p><div id="prod'.$rows.'" class="';
      if($product_info['products_isspecial']=='1')
      {
        $listing_text.='product_isspecial';
      }else{
        $listing_text.='product_regular';
      }
      $listing_text.='">';
            
            $listing_text.='<div style="width:60px;height:100px;overflow:hidden;float:left;">';
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
   
           $listing_text.='<br/><br/>'.tep_draw_form('buy_now', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&amp;buyqty=1&amp;products_id='. $product_info['products_id']), 'POST') . ''  . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART, 'align=absmiddle') . '</form>';
            if(strlen($product_info['product_desc'])>0){$listing_text.='<div style="clear:both;border:dashed 1px #cccccc;margin:3px;">' . substr($product_info['product_desc'],0,400) . '...</div>';}



  
      $listing_text.='</div></p>';

    }		       
    echo $listing_text;
		        
		        $index++;
    		    
		        $sdelim='<R N="' . $index . '">';
		        $curres=parse_section($products, $sdelim, '</R>');
    		    
		    }
		    while(strlen($curres)>1);
		    
		        
		        echo '</div></p>';
		    
		    
		    
		
		}else{
		    //Display ads
		    ?><p><br/>
		    
            </p>
		    <?php
		    
		}
		unset($product_info);
		?>		           
    
</div>
               
    </td>
    <?php
  
?>
    <!-- body_text_eof //-->
   <TD WIDTH="<?php echo BOX_WIDTH; ?>" VALIGN="top">
     <TABLE BORDER="0" WIDTH="<?php echo BOX_WIDTH; ?>" CELLSPACING="2" CELLPADDING="0">
        <!-- right_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
        <!-- right_navigation_eof //-->
      </table>
    </td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->

<?php 
$cache->addCache('hn');
}   


require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
