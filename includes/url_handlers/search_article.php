<?php


  require(DIR_WS_FUNCTIONS . '/render_products.php');
  require(DIR_WS_FUNCTIONS . '/search_tools.php');  

 $searchterm=strlen($handler['search_term'])>0 ? $handler['search_term'] : UCWords($_REQUEST['health']);
 $pagenum=(int)$_REQUEST['page'];
 if($pagenum<1){
    $pagenum=1;
    }    

 if($searchterm==''){ redir301('/'); }
 
$found_products=false;
$results['searchterm']=$searchterm;  

topic_search($results);


if($results['total_prods']>0)
{
    //Change titles to "Compare" & add manufacturer name if nescessary.
    $found_products=true;
}

if($results['total_prods']>0)
{
                  $ailments=array();
                  $uses=array();
            //Show all the products


                    $products=$results['products_id'];
            foreach($products as $pid){


                if($product_info=tep_db_fetch_array(tep_db_query('SELECT products_die, products_dieqty, products_ailments, products_uses, pd.products_isspecial, products_image, p.products_id, p.products_msrp, pd.products_head_desc_tag as product_desc, case when specials_new_products_price is not null then specials_new_products_price else p.products_price end as products_price, products_name, manufacturers_name, m.manufacturers_id
                                    from products p join products_description pd on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id left outer join specials s on s.products_id=p.products_id
                                    where p.products_id=\''.$pid.'\' and products_status=1')))
                {


                $listing_text.=renderRegularProd($product_info,$rows);
                  foreach(preg_split('/,/',$product_info['products_uses']) as $item )
                  {

                    //if(exists($uses[trim($item)]){$uses[trim($item)]+=1}else{$uses[trim($item)]=1};
                    $uses[trim($item)]+=1;
                  }
                  foreach(preg_split('/,/',$product_info['products_ailments']) as $item)
                  {
                    $ailments[trim($item)]+=1;
                  }
                  

                }               


                $index++;

            }

            arsort($uses, SORT_NUMERIC);
            arsort($ailments, SORT_NUMERIC);
            
            $ailments=array_keys($ailments);
            $uses=array_keys($uses);
            //echo $ailments[4];

}



$products_name=$searchterm;

 
?>
<!doctype html>
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
	    	
		  
		
		<?php 		    


		if($result['total_prods']>=1)
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
		    
		    

		    
		    
		
		}
            
            
            

		      if($results['total_prods']>0)
		    {
		    		    //Show all the products
                        echo $listing_text;
		                                        
	
		    
		}
		
				   
		  
          populate_backlinks();
		$hubs=match_hub_links($page_links,  true);          
		            
		            
		        

		    
		    
		unset($product_info); 
		?>		

        
        <hr class="sectiondivider"/>

		</div>
		<br style="clear:both";/><br/>
		


<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>


</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

