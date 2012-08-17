<?php
  set_time_limit(1000000);
  require('includes/application_top.php');
  ob_end_clean();
  
  
  $offset=0;
  $limit=100;
  
  $queries=site_queries::find('all', array('limit'=>$limit,'offset'=>$offset));
  
  do
  {
      
      
      if(is_array($queries))
      {
          foreach($queries as $item)
          {
              if(!preg_match('/\S*\/yoga\//i',$item->param_id))
              {
                  if(preg_match('/\S*\/topic\.php\?health\=(.+)/',$item->param_id,$matches))
                  {
                      $param_id=urldecode(str_replace('+',' ',$matches[1]));   
                      update_search_topics($param_id, $item->hits );        
                  }
                  
                  update_search_topics($item->query,$item->hits);
              }
              
          }
      }
      
      $offset+=$limit;
      
      flush();
      
  }while($queries=site_queries::find('all', array('limit'=>$limit,'offset'=>$offset)));
  
  function update_search_topics($topic, $hits)
  {
      $topic=trim(strtolower(preg_replace("/[^a-z0-9'-\.\s%]+|[+]+|\A[^a-z0-9]+|[^a-z0-9%]+\Z|^[\s]+/i",'',$topic)));
      $topic=preg_replace('/\s+/',' ',$topic);
      //echo '<br>',$topic;//ob_flush();//exit();
      
      
      if(strlen($topic))
      {
          $search_topic=search_topic::find_by_topic($topic);
          if(is_object($search_topic))
          {      
              if(isset($search_topic->updated_at) && (strtotime($search_topic->updated_at->getTimestamp())>(time()-(60*60*24)) ))
              {
                  $search_topic->hits+=$hits; //add to hits             
              }
              else
              {
                  $search_topic->hits=$hits; //reset hits
              }
              
              
              
          }
          else
          {
              $search_topic=new search_topic();
              $search_topic->topic=$topic;
              $search_topic->hits=$hits;
              
                       
          }
          
          if(empty($search_topic->approved) && (int)$search_topic->hits>100)
          {
                  $search_topic->approved=(int)1;
          }
          
          $search_topic->save();
          
          $search_topic->connection()->query('flush tables search_topics');
          
      }
      
      
      
  }
  
  
?>
