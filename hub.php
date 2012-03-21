<?php

  require_once('includes/application_top.php');  
  require(DIR_WS_FUNCTIONS . '/render_products.php');
  require(DIR_WS_FUNCTIONS . '/search_tools.php');  
  
  if(preg_match('/\/([a-z0-9-]+)$/', $_SERVER['REQUEST_URI'],$tag))  
  {
      $tag=substr($tag[0],1);
      
      if($hub =tep_db_fetch_array(tep_db_query('select p.*, pm.meta_value, pm2.meta_value as searchterm from wp_posts p join wp_postmeta pm on pm.post_id=id 
                                            left outer join wp_postmeta pm2 on pm2.post_id=pm.post_id and pm2.meta_key="searchterm"
                                            where pm.meta_key="hub" and pm.meta_value="'.tep_db_input($tag).'"')))
                                            {
                                                

  
  

 if(strlen($hub['post_content'])){
     
     
 unset($_GET);
  header("HTTP/1.0 200 OK");  
        $_REQUEST['tag']=$tag; 
        $_GET['tag']=$tag;
        $_SERVER['PHP_SELF']='/hub.php';  
        $modURL=true;

        
$found_products=false;
if(strlen($hub['searchterm']))
{
    $results['searchterm']=$hub['searchterm'];     
}
else
{
    $results['searchterm']=$tag; 
}
 

topic_search($results);


if($results['total_prods']>0)
{
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
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo $hub['post_title'] ?></title>
<meta name="description" content="<?php echo $hub['post_excerpt']?>"/>
<link rel="stylesheet" type="text/css" href="/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

		<div id="content">
        <?php
        
        $mt=preg_split('/[|]{1}/',$hub['post_title']) ;
        $first=true;
        foreach($mt as $item)
        {
            if($first)
            {
                echo '<h1>',$item,'</h1>';
                $first=false;
            }
            else
            {
                echo '<p>',$item,'</p>';                 
            }
            
            
        }
    
        ?>
		
		<div name="article" style="width:40em;float:left;margin-right:20px;">
		       <?php echo $hub['post_content']; ?>
		</div>
	    	
		  
		
		<?php 		    

            
            
            

		      if($results['total_prods']>0)
		    {
		    		    //Show all the products
                        echo '<h2>Quick Price Comparisons</h2><br/>';
                        echo $listing_text;
		                                        
	
		    
		}
		
				   
		  
		       
		            
		            
		        

		    
		    
		unset($product_info); 
		?>		

         <br/><br/>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

 }
 
 //must force an exit on this one.
 exit();
  }
  }
  
?>

