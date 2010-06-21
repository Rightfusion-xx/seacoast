<?php


  require(DIR_WS_FUNCTIONS . '/render_products.php');

 $searchterm=strlen($handler['search_term'])>0 ? $handler['search_term'] : UCWords($_REQUEST['health']);
 $pagenum=(int)$_REQUEST['page'];
 if($pagenum<1){
    $pagenum=1;
    }    

 if($searchterm==''){ redir301('/'); }
 
$found_products=false;

$gquery=GOOGLE_SEARCH_URL . "num=20&filter=0&q=inurl%3Aproduct_info&as_q=intitle%3A" . str_replace('+','+intitle%3A',urlencode($searchterm));
if($_REQUEST['page']!=''){$gquery.='&start='.(($_REQUEST['page']*20)+1); }
$products=file_get_contents($gquery); 
$total_prods=(int)parse_section($products, '<M>','</M>');

if($total_prods>0)
{
    //Change titles to "Compare" & add manufacturer name if nescessary.
    $found_products=true;
}


if($total_prods==0){
    $gquery=GOOGLE_SEARCH_URL . "num=20&filter=0&q=inurl%3Aproduct_info+" . urlencode($searchterm);
    if($_REQUEST['page']!=''){$gquery.='&start='.(($_REQUEST['page']*20)+1); }
    $products=file_get_contents($gquery); 
    $total_prods=(int)parse_section($products, '<M>','</M>');
}

if($_REQUEST['page']==''){
$gquery=GOOGLE_SEARCH_URL . "num=5&filter=0&q=inurl%3Ahealth_library+" . urlencode($searchterm);
$healthnotes=file_get_contents($gquery);}

if($_REQUEST['page']==''){
$gquery=GOOGLE_SEARCH_URL . "num=10&filter=0&as_q=Health+Encyclopedia&q=" . urlencode($searchterm). '+-health_library';
$categories=file_get_contents($gquery);}

$products_name=$searchterm;

 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo $handler['title_tag'] ?></title>
<meta name="keywords" content="<?php echo $searchterm; ?>"/>
<meta name="description" content="<?php echo $handler['description_tag']?>"/>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

		<div id="content">
		<h1><?php echo $handler['header_tag'];?></h1>
		
		<div name="article" style="width:40em;float:left;margin-right:20px;">
		       <?php echo $handler['content']; ?>
		</div>
	    	
		  
		
		<?php //search manufacturers and categories
		    



	
		
		$total_prods=(int)parse_section($products, '<M>','</M>');
		if($total_prods>=1)
		{
		?>
		    <h2>
		    Review Best <?php echo $searchterm; ?> Products</h2>
		    <p>
		  <?php
		    //Show all the products
		    $index=1;
		    if($_REQUEST['page']!=''){$index=(($_REQUEST['page']*20)+1); }
		    
		    $sdelim='<R N="' . $index . '">';
		    $curres=parse_section($products, $sdelim, '</R>');
		    

		    do{
		        
		        $pid=parse_section($curres, 'http://www.seacoastvitamins.com/product_info.php?products_id=','</U>');
		        
		        if($product_info=tep_db_fetch_array(tep_db_query('SELECT pd.products_isspecial, products_image, p.products_id, p.products_msrp, pd.products_head_desc_tag as product_desc, case when specials_new_products_price is not null then specials_new_products_price else p.products_price end as products_price, products_name, manufacturers_name, m.manufacturers_id
		                            from products p join products_description pd on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id left outer join specials s on s.products_id=p.products_id
		                            where p.products_id=\''.$pid.'\' and products_status=1'))){;
               $rows=1;

                $listing_text=renderRegularProd($product_info,$rows); 
               
                  
                  $rows++;


                }		       
                echo $listing_text;
		        
		        $index++;
    		    
		        $sdelim='<R N="' . $index . '">';
		        $curres=parse_section($products, $sdelim, '</R>');
    		    
		    }
		    while(strlen($curres)>1);
		    
		    

		    
		    
		
		}else{
		
		    $gquery=GOOGLE_SEARCH_URL . "num=20&filter=0&q=inurl%3A/cheapest/&as_q=intitle%3A" . str_replace('+','+intitle%3A',urlencode($searchterm));
            if($_REQUEST['page']!=''){$gquery.='&start='.(($_REQUEST['page']*20)+1); }
            $products=file_get_contents($gquery); 
            $total_prods=(int)parse_section($products, '<M>','</M>');

		    if($total_prods==0){
		        $gquery=GOOGLE_SEARCH_URL . "num=20&filter=0&q=inurl%3A/cheapest/+" . str_replace('+','+intitle%3A',urlencode($searchterm));
                if($_REQUEST['page']!=''){$gquery.='&start='.(($_REQUEST['page']*20)+1); }
                $products=file_get_contents($gquery); 
                $total_prods=(int)parse_section($products, '<M>','</M>');
             }
            
            
            if($total_prods==0){
		    //Display ads
		    ?>
		    <?php echo $research_topics?>
		    <p><br/>
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
		    <?php } elseif($total_prods>0)
		    {
		    		    //Show all the products
		    $index=1;
		    if($_REQUEST['page']!=''){$index=(($_REQUEST['page']*20)+1); }
		    
		    $sdelim='<R N="' . $index . '">';
		    $curres=parse_section($products, $sdelim, '</R>');
		    

		        do{
    		        
		            $pid=parse_section($curres, '/cheapest/','-');
    		        
		            if($product_info=tep_db_fetch_array(tep_db_query('SELECT * from products_new
		                                where products_id="'.$pid.'"'))){;
                   $rows=1;

                    $listing_text=renderComparisonProd($product_info,$rows); 
                   
                      
                      $rows++;


                    }		       
                    echo $listing_text;
    		        
		            $index++;
        		    
		            $sdelim='<R N="' . $index . '">';
		            $curres=parse_section($products, $sdelim, '</R>');
        		    
		           }
		         while(strlen($curres)>1);
	
		    
		}
		
				    //check for additional pages.
		    if($total_prods>0){
		        //display navigation
		        echo '<p><div style="width:100%;">';
		        $moveurl='/topic.php?health=' . strtolower(urlencode($searchterm));
		            if($pagenum>1)
		            {
		                $prevlink=$moveurl;
		                if($pagenum-1!=1){$prevlink.='&page='.($pagenum-1);}
		            }
		            
		            
		            if(((int)$pagenum)<((float)($total_prods/20))){
		                $nextlink=$moveurl.'&page='.($pagenum+1);
		                
		             }
		            
		            if(isset($prevlink)){
		                echo '<span style="float:left;"><<&nbsp;<a href="'.$prevlink.'">Previous '.$searchterm .' Information</a></span>';
		            }
		            if(isset($nextlink)){
		              echo '<span style="float:right;"><a href="'.$nextlink.'">More '.$searchterm .' Information</a>&nbsp;>></span>';
		            }
		       
		            
		            
		        
		        echo '</div></p>';}
		    }
		    
		    
		unset($product_info); 
		?>		

        
        <hr class="sectiondivider"/>
                           <?php include(DIR_WS_MODULES . 'similar_picks.php');?>
                           

                           
      
		
		                           <?php //include(DIR_WS_MODULES . 'similar_picks.php');?>
		                     
		    
		</div>
		<br style="clear:both";/><br/>
		


<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>


</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

