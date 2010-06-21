<?php

	
        $page_param=parse_section(str_replace('&','<eof>',$_SERVER['REQUEST_URI']).'<eof>','?','<eof>');
        if(1==1)//strlen($page_param)>0)
        {
        
// Select the other products in the same category

  if((int)$_REQUEST['products_id'])
  {
    $searches_query = tep_db_query("
                                    select distinct source_uri as query
                                    from seacoast_links.source_link sl
                                    join site_queries sq on sq.param_id=sl.source_uri
                                    where source_uri like '/topic.php?health=%' and 
                                    source_link='/product_info.php?products_id=" . tep_db_input((int)$_REQUEST['products_id']) ."' order by hits desc, time_created asc LIMIT 0,20;
                                    ");
    
    
  }
  else
  {
      $searches_query = tep_db_query("
                                    select distinct source_uri as query
                                    from seacoast_links.source_link sl
                                    join site_queries sq on sq.param_id=sl.source_uri
                                    where source_uri like '/topic.php?health=%' and 
                                    source_link='" . tep_db_input($_SERVER['REQUEST_URI']) ."' order by hits desc, time_created asc LIMIT 0,20;
                                    ");
  }
                                    


// Write the output containing each of the products
    while ($searches = tep_db_fetch_array($searches_query)) {
      
      $searches['query']=urldecode(substr($searches['query'], strpos($searches['query'], '=')+1));    
      $searches_string=$searches_string.'<p><a href="/topic.php?health='.urlencode(strtolower($searches['query'])).'">'.ucwords($searches['query']).'</a></p>';

    }

        }   
    

if(strlen($searches_string)>0 || strpos($_SERVER['REQUEST_URI'],'zyflamend')>0){
?>
<!-- similar_products //-->
<h2>Similar Picks By Most Popular</h2>
<p>
  <?php

    echo $searches_string;
  
    if(strpos(' '.$_SERVER['REQUEST_URI'],'/zyflamend/')>0){
      echo '<p><a href="/topic.php?health=zyflamend"><b>Discount Zyflamend</b></a></p>';
     }
    elseif(strpos($_SERVER['REQUEST_URI'],'zyflamend')>0){
      echo '<p><a href="/zyflamend/"><b>Zyflamend Benefits</b></a></p>';
     }
	  
?>
</p>
<!-- similar_products_eof //-->
<?php
  }
?>