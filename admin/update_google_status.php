<?php
$system_login=true;
  set_time_limit(1200);
  $_SERVER['DOCUMENT_ROOT']='..'; 
  require('includes/application_top.php');
  
    $ctx=stream_context_create(array(
                                     'http' => array(
                                     'timeout' => 10
                                     )
                                     )
                                     );
  
  @$status=file_get_contents(GOOGLE_SEARCH_URL . "num=20&filter=0&q=inurl%3Aproduct_info&as_q=intitle%3ATEST+PING",0,$ctx);

  if($status!=false)
  {
    $status=1;
  }
  else
  {
    $status=0;
  }
  
  tep_db_query('update configuration set configuration_value='. $status .' where configuration_key="GOOGLE_MINI_SERVING"');
  
  ?>
