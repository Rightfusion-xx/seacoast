<?php

define ('OSS_SERVING',false);     

    $ctx=stream_context_create(array(
                                     'http' => array(
                                     'timeout' => 6
                                     )
                                     )
                                     );  

function topic_search(&$results)
{ 
  global $searchcache;
  
  //$searchcache=new megacache(60*60*24*31, 'search_cache');
  $results['searchterm']=strtolower(trim($results['searchterm']));    //clean query
    
  solr_topic_search($results);
      
   if(isset($results['suggestion']))
   {
       solr_topic_search($results,$results['suggestion']);      
   }
   
   // backwords compatibility with old searches
   $results['products_id']=Array();
   foreach($results['results'] as $item)
   {
       if($item['type']=='product')
       {
           $results['total_prods']=array_push($results['products_id'],$item['products_id']);
       }
       
   }                 


}

function solr_topic_search(&$results, $keyword='')
{
          $solr_query['qf']='products_name products_description manufacturers_name products_head_desc_tag products_uses products_ailments products_departments products_price  products_msrp post_content post_title post_excerpt hub_name reviews_text';
          $solr_query['wt']='json';
          $solr_query['rows']='64';
          $solr_query['defType']='dismax';
          $solr_query['mm']=1;
          $solr_query['q']=$results['searchterm'];
          $solr_query['spellcheck.q']=$results['searchterm'];
          $solr_query['spellcheck']="true";
      
          global $ctx;
          
          if(!$keyword)
          {
              $keyword=trim($results['searchterm']);       
          }
          
          if(!$results['found_products'])
          {
              $results['found_products']=false;     
          }
          
          $con=SOLR_SEARCH_URL;
          foreach(array_keys($solr_query) as $item)
          {
              $con.=$item.'='.urlencode($solr_query[$item]).'&';
              
          }
          
          @$sr=json_decode(file_get_contents($con, 0, $ctx),true);
          
          $results['numFound']=$sr['response']['numFound'];
          if($results['numFound']>0)
          {
                //Change titles to "Compare" & add manufacturer name if nescessary.
                $results['found_products']=true;
                $results['results']=$sr['response']['docs'];
                $results['total_prods']=2;
                //$results['products_id_similar']=array_slice($results['products_id'],0,7);   
          }
        

    
}

/*
function topic_search(&$results)
{ 
  global $searchcache;
  $searchcache=new megacache(60*60*24*31, 'search_cache');
  $results['searchterm']=strtolower(trim($results['searchterm']));    //clean query
    

  if(!cached_results($results))  //load cache if available
  {                         
   healthnotes_search($results);
   category_search($results);
   product_search($results);
   if(isset($results['suggestion']))
   {
       product_search($results,$results['suggestion']);      
   }
   cache_results($results);
  }


}
*/

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

     if(($db_cache['uts']>(time()-60*60*24) || !OSS_SERVING) && ($db_cache['results']=$searchcache->doCache($results['searchterm'], false)))
    {
     
      //cache is good or mini is not running. Load it.
      
      $results=unserialize(gzuncompress($db_cache['results']));
      if( $db_cache['total_prods']==0)
      {
          //no results found. Re-run query
          unset($results);
          return(false);
      }
      else
      {
        return(true);
      }
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
  if(OSS_SERVING)
  {
   global $ctx;
   $gquery=OSS_SEARCH_URL . "rows=10&fq=urlExact:/health-guide/&q=" . urlencode($results['searchterm']);
   @$healthnotes=file_get_contents($gquery, 0, $ctx);
   $results['healthnotes']=process_oss_results($healthnotes,$results);
  }

}

function category_search(&$results)
{
  if(OSS_SERVING)
  {
    global $ctx;
  
    $gquery=OSS_SEARCH_URL . 'rows=10&fq=urlExact:'.urlencode('("http://www.seacoast.com/guide/*" or "http://www.seacoast.com/ailment/*" or "http://www.seacoast.com/remedies/*" or "http://www.seacoast.com/use/*")').'&q=' . urlencode($results['searchterm']). '';

    @$categories=file_get_contents($gquery, 0, $ctx);
  
    if(preg_match('/\<spellcheck\>(.*?)\<\/spellcheck\>/ms',$categories,$matches))
    {
        //Offer spelling recommendation.
        if(preg_match('/\<suggest\>(.*?)\<\/suggest\>/ms',$matches[1],$match))   //just get the first rec.
        {
            $results['suggestion']=$match[1];
            
        }
        
    }
  
    $results['categories'] = process_oss_results($categories,$results);
  }
  
}
   

function process_oss_results(&$result_file, &$results)
{
  $index=1;
  
  $result_list=Array();
   
  if(preg_match_all('/\<doc\s.*?\<\/doc\>/ms',$result_file, $matches))
  {
          
      
      foreach($matches[0] as $item)
      {
        $result_arr=Array();
        preg_match('/\<snippet name\=\"title\".*?\>(.*?)\<\/snippet\>/ms',$item,$m2);
        $result_arr['title']=html_entity_decode($m2[1]);
        
        preg_match('/\<field name\=\"url\"\>(.*?)\<\/field\>/ms',$item,$m2);
        $result_arr['url']=str_replace('http://www.seacoast.com/','/',urldecode($m2[1]));

        preg_match('/\<snippet name\=\"content\".*?\>(.*?)\<\/snippet\>/ms',$item,$m2); 
        $result_arr['snippet']=html_entity_decode($m2[1]);

        array_push($result_list,$result_arr);
          
      }
  }
  
  return($result_list);


}

function product_search(&$results, $keyword='')
{
  global $ctx;
  if(!$keyword)
  {
      $keyword=trim($results['searchterm']);       
  }
  
  if(!$results['found_products'])
  {
      $results['found_products']=false;     
  }


  if($products==false || !OSS_SERVING) //if no connection could be made, search using DB instead
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

function process_oss_product_results(&$results_arr, $products)
{

    if(preg_match_all('/\<doc\s.*?\<\/doc\>/ms',$products, $matches))
    {
            
      
        foreach($matches[0] as $item)
        {
            if(preg_match('/\<field name\=\"url\"\>.*?-(\d+)\w*\<\/field\>/', $item, $prod))
            {
                $pid=$prod[1];           
                
                
                if(!in_array($pid,$results_arr))
                {
                    array_push($results_arr,$pid); 
                }
            }
    		        
          }
    }
      
      //return($results_arr);


}



?>