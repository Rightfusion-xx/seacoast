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

 if($results['numFound']>0)
                {
                    foreach($results['results'] as $doc)
                    {
                        switch($doc['type'])
                        {
                            case 'product':
                                $rows++;
                                if($rows==21)
                                {
                                    $listing_text.="<h1 class=\"si\" style=\"width:100%;\border-style:none;\">You might also like these</h1>";
                                }

                                if($rows==2 && $cart->count_contents() < 1 && !$no_results)
                                {
                                    $listing_text.=<<<ADSENSE


                <div class="si" style="width:530px;text-align:center;">


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
ADSENSE;


                                }
                                $listing_text.='<div class="si">'.renderRegularProdEx($doc,$rows).'</div>';

                                                  foreach(preg_split('/,/',$doc['products_uses']) as $item )
                                              {

                                                $uses[trim($item)]+=1;
                                              }
                                              foreach(preg_split('/,/',$doc['products_ailments']) as $item)
                                              {
                                                $ailments[trim($item)]+=1;
                                              }








                                break;


                            case 'review':

                                $listing_text.=<<<REVIEW

                                <div class="si">
                                    <a href="/product_reviews_info.php?review={$doc['reviews_id']}">Review</a>
                                    <br/>{$doc['reviews_text']}
                                </div>
REVIEW;
                                break;


                            case 'hub':
                                $listing_text.=<<<HUB
                                <div class="si">
                                    <a href="/{$doc['hub_name']}">{$doc['post_title']}</a>
                                    <br/>{$doc['post_excerpt']}
                                </div>
HUB;
                                break;
                        }


                    }



            }

                                                    arsort($uses, SORT_NUMERIC);
                                        arsort($ailments, SORT_NUMERIC);

                                        $ailments=array_keys($ailments);
                                        $uses=array_keys($uses);


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
<script type="text/javascript" src="/jquery/js/jquery.masonry.min.js"></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0px;" rightmargin="0">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
 <div id="container" style="margin-left:15px;">
<?php
$parts = explode(' ', $searchterm);
$parts = "LOWER(`message_keyword`) = '" . implode("' OR LOWER(`message_keyword`) = '", $parts). "'";

$topMessage = tep_db_fetch_array(tep_db_query('
    SELECT * FROM `top_messages` WHERE ' . $parts . '
'));
if(!empty($topMessage))
{
    echo $topMessage['message_text'];
}
if($cart->count_contents() < 1 && $_SERVER['HTTPS']=='off' && $no_results)
{?>
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

<?php }


if($cart->count_contents() < 1 && $no_results){ ?>

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





     if($mflink=link_exists('/natural_uses.php?use='.urlencode(strtolower($usename)),$page_links))
                          {
                            $benefits='<a href="'.$mflink.'">'.ucwords($usename).' '.$product_info['products_type'].'</a> &nbsp;'.$benefits;
                          }

     ?>

         <div class="container">
            <div class="row">
                <div class="span12">



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

                require(DIR_WS_BOXES . 'related_searches.php');



            ?>

            <?php echo $listing_text;?>
           </div>
           <script language="javascript">

var $container = $('#listings');
$container.imagesLoaded(function(){
  $container.masonry({
    itemSelector : '.si',
    columnWidth : 10
  });
});

         </script>




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
            </div>
         </div>


</div>

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

