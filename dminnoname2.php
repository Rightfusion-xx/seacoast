<?php
  require('includes/application_top.php');
  
  $queries=site_queries::find('all', array('limit'=>10));
  
  if(is_array($queries))
  {
      foreach($queries as $item)
      {
          preg_match('/\S*\/topic\.php\?health\=(.+)',$item->param_id,$matches);
          print_r($matches);
      }
  }
  
  
?>
