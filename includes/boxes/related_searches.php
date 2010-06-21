<?php

    
        $page_param=parse_section(str_replace('&','<eof>',$_SERVER['REQUEST_URI']).'<eof>','?','<eof>');
        if(1==1)//strlen($page_param)>0)
        {
        
// Select the other products in the same category
    $searches_query = tep_db_query("select query,  hits FROM site_queries WHERE param_id='" . tep_db_input($_SERVER['REQUEST_URI']) ."' order by hits desc, time_created asc LIMIT 0,20" );

    
// Write the output containing each of the products
    while ($searches = tep_db_fetch_array($searches_query)) {
    
      $searches_string=$searches_string.'<p><a href="/topic.php?health='.urlencode(strtolower($searches['query'])).'">'.ucwords($searches['query']).'</a></p>';

    }
      
        }   
    

if(strlen($searches_string)>0 || strpos($_SERVER['REQUEST_URI'],'zyflamend')>0){
?>
<!-- similar_products //-->
<div id="nav_manufacturers" class="nav_box">

  <div class="nav_header">Similar Picks</div>
  <?php

    echo $searches_string;
  
    if(strpos(' '.$_SERVER['REQUEST_URI'],'/zyflamend/')>0){
      echo '<p><a href="/topic.php?health=zyflamend"><b>Discount Zyflamend</b></a></p>';
     }
    elseif(strpos($_SERVER['REQUEST_URI'],'zyflamend')>0){
      echo '<p><a href="/zyflamend/"><b>Zyflamend Benefits</b></a></p>';
     }
      
?>
</div>
<!-- similar_products_eof //-->
<?php
  }
?>