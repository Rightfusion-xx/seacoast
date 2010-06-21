<?php
$system_login=true;
  set_time_limit(60*10);
  $_SERVER['DOCUMENT_ROOT']='..';
  require('includes/application_top.php');
  
  $result=array();

  
  
  $product_query=tep_db_query('select products_departments, products_uses, products_ailments, products_keywords from products p join products_description pd
    on p.products_id=pd.products_id');
    
    while($prod=tep_db_fetch_array($product_query))
    {
        
        // we have products. Explode all the descriptors
        foreach(array_keys($prod) as $item)
        {
            foreach(preg_split('/(,|$)/i',$prod[$item]) as $info)
            {
                if(trim($info)!='')
                {
                    $results[$item][mb_convert_encoding(strtolower(trim($info)),"ASCII")]+=1;                    
                }               
                
            }
            
        }
        
       
        
        
    }
    
    foreach(array_keys($results) as $item)
    {
        tep_db_query('drop table if exists '.tep_db_input($item));
        tep_db_query('create table if not exists '.tep_db_input($item).' ('. tep_db_input($item).' varchar(255) primary key, count int not null)');  
        foreach(array_keys($results[$item]) as $info)
        {
            tep_db_query('insert into '.tep_db_input($item).' values("'.tep_db_input($info).'",'.(int)$results[$item][$info].')');
        }  
        @tep_db_query('create index '.$item.'_count on '.tep_db_input($item).' (count)');                                                             
    }

?>