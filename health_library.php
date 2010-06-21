<?php


  require('includes/application_top.php');


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  $org = 'seacoast';
$contentUrl = 'http://www.healthnotes.info/http/healthnotes.cfm'; 

$url = "{$contentUrl}?org={$org}";
if(!isset($_GET['article'])){
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
else{
    $url=$url."&ContentID={$_GET['article']}";
    $hn=file_get_contents($url);
    if(strpos($hn,'<div id="Copyright-Notice"')){
     $hn=substr($hn,0,strpos($hn,'<div id="Copyright-Notice"',0));
    }
    
    
    $hn=str_replace('5-Hydroxytryptophan','5-Hydroxytryptophan (5-HTP)',$hn);

    $metaCategory=substr($hn,strpos($hn,'<META NAME="category" CONTENT="')+31,100);
    $metaCategory=substr($metaCategory,0,strpos($metaCategory,'">'));

    $pageTitle=substr($hn,strpos($hn,'<title>')+7,500);
    $pageTitle=substr($pageTitle,0,strpos($pageTitle,'</title>'));
    $topic=$pageTitle;
    $searchterm=$topic;


    switch(strtolower($metaCategory))
    {
        case 'concern':
            $pageTitle=$pageTitle . ' | Health Concerns & Risks';
            break;
        case 'supp':
            $pageTitle=$pageTitle . ' | Natural Supplements';
            break;
        case 'herb':
            $pageTitle=$pageTitle . ' | Herbal Details, Reviews & Effectiveness';
            break;
        case 'herb_drugix':
            $pageTitle=$pageTitle . ' | Herb & Prescription Drug Interactions';
            break;
        case 'drug':
            $pageTitle=$pageTitle . ' | Prescription Drug Details, Risks & Natural Alternatives';
            break;
        case 'index':
            $pageTitle=$pageTitle . ' | Natural Health Center';
            break;
        case 'diet':
            $pageTitle=$pageTitle . ' | Healthy & Natural Dieting';
            break;
        case 'center':
            $pageTitle=$pageTitle . ' | Natural Health Guide';
            break;
        case 'healthy_eating':
            $pageTitle=$pageTitle . ' | Eating Healthy';
            break;
        case 'food_guide':
            $pageTitle=$pageTitle . ' | Natural Food Guide';
            break;
        case 'lifestyle':
            $pageTitle=$pageTitle . ' | Healthy Lifestyle, Naturally!';
            break;
        case 'supp_drugix':
            $pageTitle=$pageTitle . ' | Side Effects & Drug Interactions';
            break;
        default:
            $pageTitle=$pageTitle . ' | Natural Health Research';
            break;
    }
    $metaDescription=substr($hn,strpos($hn,'<META NAME="description" CONTENT="')+34,500);
    $metaDescription=substr($metaDescription,0,strpos($metaDescription,'">'));

    $metaKeywords=substr($hn,strpos($hn,'<META NAME="keywords" CONTENT="')+31,500);
    $metaKeywords=substr($metaKeywords,0,strpos($metaKeywords,'">'));
}
if(strpos($hn,'"Article-Title">')>0)
{
    $hn=parse_section($hn.'<a name="Reference-List"', '"Article-Title">','<a name="Reference-List"');
    $hn=parse_section($hn.'<div id="Resource-List"', '"Article-Title">','<div id="Resource-List"');
    //$hn=parse_section('<a name="Reference-List"'.$hn.'<div id="Bibliography"', '"Article-Title">','<div id="Bibliography"');

    $hn=substr($hn, strpos($hn,'</div>')+6);
 
}
if(strpos($hn,'<div class="sidebar-content">')>0)
{
    $hn=str_replace(parse_section($hn.'</div>', '<div class="sidebar-content">','<div '),'',$hn);
 
}

$hn=str_replace('healthnotes.php','health_library.php',$hn);
$hn=str_replace('org=seacoast&ContentID','article',$hn);
$hn=str_replace('org=seacoast&contentid','article',$hn);
$hn=str_replace('org=seacoast&','',$hn);


    
//Do Google Searches
if($_REQUEST['page']==''){
$gquery=GOOGLE_SEARCH_URL . "num=10&filter=0&as_q=Health+Encyclopedia&q=" . urlencode($searchterm). '+-health_library';
$gcategories=file_get_contents($gquery);}

$gquery=GOOGLE_SEARCH_URL . "num=20&filter=0&q=inurl%3Aproduct_info+" . urlencode($searchterm);
if($_REQUEST['page']!=''){$gquery.='&start='.(($_REQUEST['page']*20)+1); }
$products=file_get_contents($gquery);


		
		
 


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>"> 
<meta name="description" content="<?php echo $metaDescription;?>">
<meta name="keywords" content="<?php echo $metaKeywords;?>">
<title><?php echo $pageTitle; ?></title>

<link rel="stylesheet" type="text/css" href="stylesheet.css">
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
                      
    echo $hn;
    if(!isset($_GET['article']))
    {
    ?>
          <?php require(DIR_WS_INCLUDES . 'hntop.php'); ?>
    <?php
    }

    
		
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
		    <script src="http://img.shopping.com/sc/pac/shopwidget_v1.0_proxy.js"> </script>
                <script>
                <!--
                   // Seacoast Product Page Comparison
                   var sw = new ShopWidget();
                   sw.mode            = "kw";
                   sw.width           = 728;
                   sw.height          = 90;
                   sw.linkColor       = "#0033cc";
                   sw.borderColor     = "#ffffff";
                   sw.fontColor       = "#000000";
                   sw.font            = "arial";
                   sw.linkin          = "8024494";
                   sw.categoryId      = "205";
                   sw.keyword         = "<?php echo $searchterm;?>";
                   sw.render();
                //-->
                </script>
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
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
