<?php

    $ctx=stream_context_create(array(
                                     'http' => array(
                                     'timeout' => 6
                                     )
                                     )
                                     );



function topic_search(&$results)
{ 
  global $searchcache;
  $searchcache=new megacache(60*60*24*31, 'search_cache');
  $results['searchterm']=strtolower(trim($results['searchterm']));    //clean query
    

  if(!cached_results($results))  //load cache if available
  {                            
   product_search($results);
   healthnotes_search($results);
   category_search($results);
   cache_results($results);
  }


}

function cache_results(&$results)
{
  global $searchcache;
  tep_db_query('update search_cache set total_prods='.tep_db_input($results['total_prods']).', found_products='.tep_db_input((int)$results['found_products']).' where query="'.tep_db_input($results['searchterm']).'";');
  $searchcache->addCache($results['searchterm'],gzcompress(serialize($results),9));
}

function cached_results(&$results)
{
  global $searchcache;
  if($db_cache=tep_db_fetch_array(tep_db_query('select *, UNIX_TIMESTAMP(time_updated) as uts from search_cache where query="'.tep_db_input($results['searchterm']).'" limit 0,1')))
  {
    //found cache. Check date

     if(($db_cache['uts']>(time()-60*60*24) || !GOOGLE_MINI_SERVING) && ($db_cache['results']=$searchcache->doCache($results['searchterm'], false)))
    {
     
      //cache is good or mini is not running. Load it.
      
      $results=unserialize(gzuncompress($db_cache['results']));
      return(true);
    }
    else
    {
      //cache is old. run search
      return(false);
    }
  }
  else //no cache found, so create one.
  {
    tep_db_query('insert into search_cache(query) values("'.tep_db_input($results['searchterm']).'");');
  }
  return(false);

  


}

function healthnotes_search(&$results)
{
  if(GOOGLE_MINI_SERVING)
  {
   global $ctx;
   $gquery=GOOGLE_SEARCH_URL . "num=5&filter=0&q=inurl%3Ahealth_library+" . urlencode($results['searchterm']);
   @$healthnotes=file_get_contents($gquery, 0, $ctx);
   $results['healthnotes']=process_google_results($healthnotes,$results);
  }

}

function category_search(&$results)
{
  if(GOOGLE_MINI_SERVING)
  {
    global $ctx;
  
    $gquery=GOOGLE_SEARCH_URL . "num=10&filter=0&as_q=Health+Encyclopedia&q=" . urlencode($results['searchterm']). '+-health_library';
    @$categories=file_get_contents($gquery, 0, $ctx);
  
    if(strpos($categories,'<Suggestion q=')>0)
    {
        //Offer spelling recommendation.
        $results['suggestion']=substr($categories, strpos($categories,'<Suggestion q="',0)+15,
                    strpos($categories, '"', strpos($categories, '<Suggestion q="',0)+15)
                    -(strpos($categories,'<Suggestion q="',0)+15)-20);
    }
  
    $results['categories'] = process_google_results($categories,$results);
  }
  
}

function process_google_results(&$result_file, &$results)
{
  $index=1;
		    
  $sdelim='<R N="' . $index . '">';
  $curres=parse_section($result_file, $sdelim, '</R>');
  
  $result_list=Array();

  do{
    $result_arr=Array();
    $result_arr['title']=html_entity_decode(parse_section($curres, '<T>', '</T>'));
    $result_arr['url']=str_replace('http://www.seacoastvitamins.com/','',urldecode(parse_section($curres, '<UE>', '</UE>')));
    if(!$results['total_prods'])$result_arr['snippet']=html_entity_decode(parse_section($curres, '<S>', '</S>'));

    $index++;
  
    $sdelim='<R N="' . $index . '">';
    $curres=parse_section($result_file, $sdelim, '</R>');
    array_push($result_list,$result_arr);
  }
  while(strlen($curres)>1);
  
  return($result_list);


}

function product_search(&$results)
{
  global $ctx;
  $keyword=trim($results['searchterm']);
  $results['found_products']=false;

  //if more than one day, begin search
  if(GOOGLE_MINI_SERVING)
  {
    
    //attempt google search on titles
    $gquery=GOOGLE_SEARCH_URL . "num=40&filter=0&q=inurl%3Aproduct_info&as_q=intitle%3A" . str_replace('+','+intitle%3A',urlencode($keyword));
    //if($_REQUEST['page']!=''){$gquery.='&start='.(($_REQUEST['page']*20)+1); }
    @$products=file_get_contents($gquery,0,$ctx);

    $results['total_prods']=(int)parse_section($products, '<M>','</M>');
    $results['products_id']=Array();
    if($results['total_prods']>0)
    {
        //Change titles to "Compare" & add manufacturer name if nescessary.
        $results['found_products']=true;
        $results['products_id']=process_google_product_results($results, $products);
        $results['total_prods']=count($results['products_id']);
    }
    
    //run search for similar products module, or if no products are found
    $gquery=GOOGLE_SEARCH_URL . "num=40&filter=0&q=inurl%3Aproduct_info+" . urlencode($keyword);

    @$products=file_get_contents($gquery,0,$ctx);

    $results['products_id_similar']=process_google_product_results($results, $products);
    if($results['total_prods']<1)  //set the main results to the similar products and update the count
    {
      $results['total_prods']=(strlen($results['products_id_similar'][0])>0) ? count($results['products_id_similar']) : 0 ;
      $results['products_id']=$results['products_id_similar'];
    }
    $results['products_id_similar']=array_slice($results['products_id_similar'],0,7);

  }
  

  if($products==false || !GOOGLE_MINI_SERVING) //if no connection could be made, search using DB instead
  {

       
       //Do DB search on titles
       $dbquery=tep_db_query('select p.products_id from products_description pd join products p on p.products_id=pd.products_id where products_head_title_tag like "%'.str_replace(' ','%',$keyword).'%" and products_status=1 order by products_name limit 0,40');
       $results['products_id']=Array();
       while($prod=tep_db_fetch_array($dbquery))
       {
         $results['total_prods']=array_push($results['products_id'],$prod['products_id']);
         
       }

       if($results['total_prods']>0)
       {
         $results['found_products']=true;
       }
       
       //do search for similar products
       $dbquery=tep_db_query('SELECT products.products_id FROM products_description JOIN products ON products.products_id=products_description.products_id WHERE MATCH(products_description,products_head_title_tag,products_head_desc_tag,products_departments,products_ailments,products_uses) AGAINST("'.$keyword.'" IN NATURAL LANGUAGE MODE) AND products_status=1 LIMIT 0,40');
       $results['products_id_similar']=Array();
       while($prod=tep_db_fetch_array($dbquery))
       {
         array_push($results['products_id_similar'],$prod['products_id']);
       }

        if($results['total_prods']<1)  //set the main results to the similar products and update the count
        {
          $results['total_prods']=count($results['products_id_similar']);
          $results['products_id']=$results['products_id_similar'];
        }
        $results['products_id_similar']=array_slice($results['products_id_similar'],0,7);

  }

}

function process_google_product_results(&$results, $products)
{

      $index=1;
      $sdelim='<R N="' . $index . '">';
      $results_arr=Array();
      $curres=parse_section($products, $sdelim, '</R>');
      do{
        $pid=parse_section($curres, 'http://www.seacoastvitamins.com/product_info.php?products_id=','</U>');
        array_push($results_arr,$pid);

        $index++;
        $sdelim='<R N="' . $index . '">';
        $curres=parse_section($products, $sdelim, '</R>');
    		    
      }while(strlen($curres)>1);
      
      return($results_arr);


}



?>