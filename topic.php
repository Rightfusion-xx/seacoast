<?php


  require('includes/application_top.php');
  
  //$stop_tidy=true;
  
  if(preg_match('/[A-Z]/',$_SERVER['REQUEST_URI']))
  {
      redir301(strtolower($_SERVER['REQUEST_URI']));
  }

  //Only search if valid. clean search request
  $_REQUEST['health']=str_replace("\'","'",$_REQUEST['health']);
  $searchterm=trim(UCWords($_REQUEST['health']));
  $strip_search=str_replace('  ',' ',preg_replace("/[^a-zA-Z0-9s_\-\s']/", "", $searchterm));
  
  
  if($searchterm!=$strip_search)
  {
    redir301('/topic.php?health='.urlencode($strip_search));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
  require(DIR_WS_FUNCTIONS . '/render_products.php');
  require(DIR_WS_FUNCTIONS . '/search_tools.php');

 
 $pagenum=(int)$_REQUEST['page'];
 if($pagenum<1){
    $pagenum=1;
    }

 if($searchterm==''){ redir301('/'); }

$results['searchterm']=$searchterm;

//Find hub articles

if($hub =tep_db_fetch_array(tep_db_query('select meta_value from wp_postmeta pm where pm.meta_key="hub" and meta_value="'.tep_db_input($searchterm).'"')))
{
    
    redir301('/'.strtolower($hub['meta_value']));
    exit();
}      //else, check for tags
elseif($hub =tep_db_fetch_array(tep_db_query('select meta_value from wp_terms t join wp_term_taxonomy tt on t.term_id=tt.term_id and tt.taxonomy="post_tag" join wp_term_relationships tr on tt.term_taxonomy_id=tr.term_taxonomy_id join wp_postmeta pm on pm.post_id=tr.object_id where t.name="'.tep_db_input($searchterm).'" and meta_key="hub"')))
{
    redir301('/'.strtolower($hub['meta_value'])); 
    exit();
}
  


//Find KeyMatches
$keymatch=tep_db_fetch_array(tep_db_query('SELECT URL FROM keymatch WHERE ("'.strtolower($searchterm).'"=SearchTerm AND MatchType="ExactMatch") OR ("'.strtolower($searchterm).'" LIKE CONCAT("%",SearchTerm,"%") AND (MatchType="PhraseMatch" OR MatchType="KeywordMatch"))'));

if(strlen($keymatch['URL'])>0)
{redir301($keymatch['URL']);}


// check to see if this is the best page to land on, based on search results.


    /*//////////////////////////////////      SHopping.com Code - Test only

                     if (! ($xmlparser = xml_parser_create()) )
                       {
                       echo ("Cannot create parser");
                       }
                    $shopping_com=file_get_contents(SHOPPING_COM_REST.'&keyword='.urlencode($searchterm));
                    echo SHOPPING_COM_REST.'&keyword='.urlencode($searchterm).'<br><br><br>';
                    echo $shopping_com;
                    exit();
                    
                    xml_set_element_handler($xmlparser, "shopOpenTag", "shopCloseTag");
                    xml_set_character_data_handler($xmlparser, "shopContents");
                    xml_parse($xmlparser, $shopping_com);
                    
                    
                    
                    
    ////////////////////////////////////////     */
topic_search($results);
if($results['total_prods']<1)
{
    unset($results);
  //rerun search using most commonly referred keyword.
  $searches_query = tep_db_query("select query,  hits FROM site_queries WHERE param_id='" . tep_db_input($_SERVER['REQUEST_URI']) ."' order by hits desc, time_created asc LIMIT 0,1" );
  if($altsearch=tep_db_fetch_array($searches_query))
  {
    $disablesuggestion=false;
    if(!isset($results['suggestion']))
    {
        $disablesuggestion=true;
    }
      $results['searchterm']=$altsearch['query'];
      topic_search($results);
      $results['searchterm']=$searchterm;
      
      if($disablesuggestion)
      {
          unset($results['suggestion']);
      }
  }
  
}  

  
/*
$gl=parse_section($products, '<GL>','</GL>');
if(strlen($gl)>0)
{redir301($gl);}

*/
if($_REQUEST['gad']=='true')
{
		if($results['total_prods']>=1)
		{
		    //redirect to product
		    $index=1;
		    
		    redir301('/product_info.php?products_id='.$results['products_id'][0]);
		}
		 

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
		                            where p.products_id=\''.$pid.'\' and products_status=1'))){;


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

$products_name=$resultsearchterm;
$no_results=false;

if($results['found_products'])
{
  $title=ucwords($searchterm).' | Vitamin Supplement for ' .ucwords($searchterm) . ' ';
  $description='Learn about ' .$searchterm . ' and find vitamins and supplements priced at wholesale cost or below for ' .$ailments[2] . ', ' . $ailments[0] . ', ' . $ailments[1] .' ...';
  $paragraph='Below are <b> ' .$searchterm.'</b> related alternative medicine supplements and vitamins. Also explore information on  <?php echo $searchterm; ?> treatment, health benefits & side effects with '.$searchterm.' products. Many of the sources come from our Encyclopedia of Natural Health
		        and include relevant health topics. Uses vary, but may include ' .$uses[0] . ', ' . $uses[1] . ', and ' . $uses[2] .' and are non-FDA reviewed or approved, natural alternatives, to use
                        for ' .$ailments[0] . ', ' . $ailments[1] . ', and ' . $ailments[2] .'. '. $searchterm.' products are reviewed below.';
  
}
elseif($results['total_prods']>0) //secondary tier
{
  $title=ucwords($searchterm).' | Treatments, Benefits & Side Effects';
  $description='' .$searchterm . ' information for ' .$uses[2] . ', ' . $uses[0] . ', ' . $uses[1] .' ...';
  $paragraph='Research <b> ' .$searchterm.'</b> with our natural health encyclopedia and product reviews. Information on  '.$searchterm.'  include treatment, health benefits & side effects. '. $searchterm.' products are reviewed below for non FDA-reviewed or approved uses such as ' .$uses[1] . ', ' . $uses[0] . ', and ' . $uses[2] .'. Related topics include ' .$ailments[2] . ', ' . $ailments[1] . ', and ' . $ailments[0] .'.';

  
}
else // Third tier, no results found
{
  $no_results=true;
  $title=ucwords($searchterm).'';
  $description='Your guide to ' .$searchterm . ' for naturally healthy living.';
  $paragraph='Your inquiry for <b> ' .$searchterm.'</b> has been logged, but has been removed from our database or does not contain relevant health topics at the moment. 
                   We tend to our Health Encyclopedia constantly, and will review this inquiry for products and information regarding this topic in the future. In the meantime, please refine
                   the health topic you are interested in, or select from the categories above.';

}

 
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo $title; ?> </title>
<meta name="keywords" content="<?php echo $searchterm; ?>"/>
<meta name="description" content="<?php echo $description; ?>"/>
<link rel="stylesheet" type="text/css" href="stylesheet.css">

<script type="text/javascript" src="/jquery/js/jquery-1.3.2.min.js"></script>  



</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<?php if($cart->count_contents() < 1 && $_SERVER['HTTPS']=='off' && $no_results){?>
<div name="chitika" style="margin:30px 0 30px 0;text-align:center;display:block;">
 <!-- Chitika -->
<script type="text/javascript"><!--
ch_client = "NealBozeman";
ch_type = "mpu";
ch_width = 550;
ch_height = 250;
ch_non_contextual = 4;
ch_vertical ="premium";
ch_sid = "Topic Pages";
var ch_queries = new Array( );
var ch_selected=Math.floor((Math.random()*ch_queries.length));
if ( ch_selected < ch_queries.length ) {
ch_query = ch_queries[ch_selected];
}
//--></script>
<script  src="http://scripts.chitika.net/eminimalls/amm.js" type="text/javascript">
</script>   

</div>

<?php } ?>


<!-- body //-->
<TABLE BORDER="0" WIDTH="100%" CELLSPACING="0" CELLPADDING="0">




  <TR> 
    <TD WIDTH="300px" VALIGN="top" rowspan="2" nowrap>
    		<?php
			if($cart->count_contents() < 1 && !$no_results){?>
				<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header" style="margin-top:20px;" ><?php if(strlen($searchterm)<=30) echo $searchterm; ?> Resources</div>
						<div style="text-align:center;margin-top:30px;margin-bottom:30px;">
							<script type="text/javascript"><!--
							google_ad_client = "pub-6691107876972130";
							/* 336x280, created 11/16/08 */
							google_ad_slot = "8781590545";
							google_ad_width = 336;
							google_ad_height = 280;
							//-->
							</script>
							<script type="text/javascript"
							src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
							</script>
						</div>
						</div>
								
			<?php }elseif($cart->count_contents() < 1 && $no_results){ ?>  	
            
            <script type="text/javascript" charset="utf-8">
                HL_WIDGET_OPTIONS = {
                "linksResultsPageUrl" : "",
                "widgetID" : 613,
                "customerID" : 17672
                }
            </script>
            <script src="http://js.hotkeys.com/js/widget/hotlinks.js" type="text/javascript" charset="utf-8" id="hotlinksScript"></script>
                  
<?php
            }
            
		if(!empty($results['healthnotes'][0]['title']))
		{


		?>
		<div id="nav_manufacturers" class="nav_box" style="width:300px;margin:5px;margin-top:20px;text-indent:0px;">
                <div class="nav_header" style="width:300px;">
		    <?php if(strlen($searchterm)<=30) echo $searchterm; ?> Research
		    </div>
		  <?php
		    //Show all the research articles

		    $research_topics.='<h2>Research Topics</h2>';

		    foreach($results['healthnotes'] as $item){
		        
		        echo '<p><a style="text-indent:0px;" href="' . $item['url'] . '">'. $item['title'] . '</a></p>';

		        $index++;


		        $research_topics.='<p style="width:600px;"><a href="'.$item['url'].'">'.$item['title'].'</a><br>'
		                            .$item['snippet'].'
		                            </p>';
    		    
		    }

		    echo '</p></div>';
		
		}
		
				if(!empty($results['categories'][0]['title']))
		{
		?>
		    <div id="nav_manufacturers" class="nav_box" style="width:300px;border:solid 1px #999999;margin:5px;margin-top:20px;text-indent:0px;margin-bottom:20px;">
            <div class="nav_header">
		    <?php if(strlen($searchterm)<=30) echo $searchterm; ?> Categories</div>
		    <p>
		  <?php
		    //Show all the categories

		    
		    $research_topics.='<h2>Find Products</h2>';

		    foreach($results['categories'] as $item){

		        echo '<p><a href="' . $item['url'] . '">'. $item['title'] . '</a></p>';
		        
		        $index++;
    		    
		        $sdelim='<R N="' . $index . '">';
		        $curres=parse_section($categories, $sdelim, '</R>');
		        
		        $research_topics.='<p style="width:600px;"><a href="'.$item['url'].'">'.$item['title'].'</a><br>'
                    .$item['snippet'].'
                    </p>';

    		    
		    }
		    while(strlen($curres)>1);
		    
		    echo '</p></div>';
		
		}		


	 require(DIR_WS_BOXES . 'related_searches.php'); 
     
     if($mflink=link_exists('/natural_uses.php?use='.urlencode(strtolower($usename)),$page_links))
                          {
                            $benefits='<a href="'.$mflink.'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$benefits;
                          }
     
     ?>
     
     


<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
     </TD> 
<td valign="top" colspan="2" valign="top"><?php require(DIR_WS_INCLUDES . 'titlebar.php'); ?></td></tr><tr><!-- body_text //-->
    <td width="100%" valign="top">
		<div id="content">
		<?php
        
        $mt=preg_split('/[|]{1}/',$title) ;
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
		
		<?php 		if($pagenum==1){ ?>
	

		        <p>
	                   <?php echo $paragraph;?>

		        </p>
		
		        
		<?php } ?>  
		
		<?php
		if($cart->count_contents() < 1 && !$no_results){?>
		
					<div style="margin-top:3em;margin-bottom:3em;">
						<script type="text/javascript"><!--
							google_ad_client = "pub-6691107876972130";
							/* Article Page Leader Board */
							google_ad_slot = "7209392701";
							google_ad_width = 728;
							google_ad_height = 90;
							//-->
							</script>
							<script type="text/javascript"
							src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
						</script>
					</div>
		
		<?php }if($cart->count_contents() < 1 && $no_results){ ?>
        <div style="margin-top:3em;margin-bottom:3em;">
        <script type="text/javascript" charset="utf-8">
            HL_WIDGET_OPTIONS = {
            "linksResultsPageUrl" : "",
            "widgetID" : 612,
            "customerID" : 17672
            }
        </script>
        <script src="http://js.hotkeys.com/js/widget/hotlinks.js" type="text/javascript" charset="utf-8" id="hotlinksScript"></script>
        </div>
		
		
		<?php 
        }
        
         //search manufacturers and categories
		    


		if(strlen($results['suggestion'])>0)
		{
		    //Offer spelling recommendation.
		    $results['suggestion']=htmlspecialchars_decode($results['suggestion'], ENT_QUOTES);
		    $results['suggestion']=str_replace(' -health_library','',$results['suggestion']);

		    echo '<p><span style="color:red;font-size:12pt;font-weight:bold;">Did you mean, <A href="/topic.php?health=' . strtolower(urlencode($results['suggestion'])) . '">' . $results['suggestion'] .'</a>?</span></p>';

		}
		
		
		
		

		


		

		
		//$total_prods=(int)parse_section($products, '<M>','</M>');
		if($results['total_prods']>=1)
		{
		?>
		    <h2>
		    <?php echo $searchterm; ?> Benefits,  Reviews & Discounts</h2>
		    <p> <div id="listings">
      
                    <?php
		    
		   echo $listing_text;
           
           ?>
           </div>
           
           
           

           
           
                <div id="displaylistings"><!-- off --></div>
    
           
           <script language="javascript">
            var $original='';
            window.listoriginal=$('#listings').html();
            var $bestresults='';
            var $moreresults='';
            if($('#displaylistings').html().indexOf('on')>0)
            {
                $('#listings').css({display:'none'});
                
                $prods=$('#listings').children('div');
                
                if($prods)
                {
                        
                    
                    do
                    {
                        if($prods.html().indexOf('Enzymatic Therapy')>0 || $prods.html().indexOf('Nature\'s Way')>0)
                        {
                            
                            $bestresults+='<div id="prod" class="product_regular">'+$prods.html()+'</div>';
                        }          
                        else
                        {
                            $moreresults+='<div id="prod" class="product_regular">'+$prods.html()+'</div>';
                        } 
                        
                        $prods=$prods.next();
                        
                        
                    }
                    while($prods.html()!=null);
                    
                    
                    $('#listings').html($bestresults + $moreresults);
                    $('#listings').css({display: 'block'});
                
                }
                
            }
           
           </script>
           
           <?php
    


		    
		
		}elseif(GOOGLE_MINI_SERVING){
		
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
            
            
            if($results['total_prods']==0){
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
		    <?php }  elseif($total_prods>0)
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
		    if($results['total_prods']>0){
		        //display navigation
		        echo '<p><div style="width:100%;">';
		        $moveurl='/topic.php?health=' . strtolower(urlencode($searchterm));
		            if($pagenum>1)
		            {
		                $prevlink=$moveurl;
		                if($pagenum-1!=1){$prevlink.='&page='.($pagenum-1);}
		            }
		            
		            
		            if(((int)$pagenum)<((float)($results['total_prods']/20))){
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
            populate_backlinks();
            $hubs=match_hub_links($page_links,  true);   
		    
		    
		unset($product_info); 
		?>


<?php if(!$no_results)
{
	?>
	<div style="margin:30px 0 30px 0;text-align:center;display:block;">
	<script type="text/javascript"><!--
		google_ad_client = "pub-6691107876972130";
		/* Article Page Bottom */
		google_ad_slot = "9749903694";
		google_ad_width = 728;
		google_ad_height = 90;
		//-->
		</script>
		<script type="text/javascript"
		src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
	</div>
	<?php
}

?>


</div>
		<br/><br/>
		</td>

<!-- body_text_eof //-->
</TR></TABLE>
<!-- body_eof //-->

<!-- footer //-->                                         
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>

<!-- Google Website Optimizer Tracking Script -->
<script type="text/javascript">
if(typeof(_gat)!='object')document.write('<sc'+'ript src="http'+
(document.location.protocol=='https:'?'s://ssl':'://www')+
'.google-analytics.com/ga.js"></sc'+'ript>')</script>
<script type="text/javascript">
try {
var gwoTracker=_gat._getTracker("UA-207538-3");
gwoTracker._trackPageview("/0386199624/test");
}catch(err){}</script>
<!-- End of Google Website Optimizer Tracking Script -->   

</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

