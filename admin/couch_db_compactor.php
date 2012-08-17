<?php
$system_login=true;
  set_time_limit(1200);
  $_SERVER['DOCUMENT_ROOT']='..';
  require('includes/application_top.php');

   //compact nescessary databases and views
    $context=stream_context_create(Array('http'=>Array('method'=>'POST','header'=>'Content-Type: application/json')));
    echo file_get_contents(COUCH_DB_ADDRESS.'seacoast-page-links/_compact',false,$context);                                                                        
    
    echo file_get_contents(COUCH_DB_ADDRESS.'seacoast-page-links/_compact/page_links',false,$context); 

?>