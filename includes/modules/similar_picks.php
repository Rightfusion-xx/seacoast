<?php


        $page_param=parse_section(str_replace('&','<eof>',$_SERVER['REQUEST_URI']).'<eof>','?','<eof>');
        if(1==1)//strlen($page_param)>0)
        {
        
// Search for similar links, e.g. similar searches

  if((int)$_REQUEST['products_id'])
  {
    $searches_query = tep_db_query("
                                    select query
                                    from site_queries sq  
                                    where
                                                               
                                    param_id='/product_info.php?products_id=" . 
                                    tep_db_input((int)$_REQUEST['products_id']) ."' 
                                    order by hits desc, time_created asc LIMIT 0,20;
                                    ");
    
    
  }
  else
  {
      $searches_query = tep_db_query("
                                    select query
                                    from site_queries sq 
                                    where param_id='" . tep_db_input($_SERVER['REQUEST_URI']) ."' order by hits desc, time_created asc LIMIT 0,20;
                                    ");
  }
                                    


// Write the output containing other search links
    while ($searches = tep_db_fetch_array($searches_query)) {
      
      $searches['query']=urldecode(substr($searches['query'], strpos($searches['query'], '=')+1));    
      $searches_string=$searches_string.'<p><a href="/topic.php?health='.urlencode(strtolower($searches['query'])).'">'.ucwords($searches['query']).'</a></p>';

    }

        }   
    
 
