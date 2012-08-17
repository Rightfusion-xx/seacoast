<?php


set_time_limit(5000);
require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

tep_db_query('drop table if exists ailments');
tep_db_query('create table ailments (ailment varchar(255),popularity int)');
tep_db_query('create index ailments on ailments (ailment)');
tep_db_query('drop table if exists symptoms');
tep_db_query('create table symptoms (symptom varchar(255),popularity int)');
tep_db_query('create index symptoms on symptoms (symptom)');
echo 'ailments dropped, recreated<br />running...<br/>';

$prods_query=tep_db_query('select p.products_id, pd.products_ailments, pd.products_uses from products p join products_description pd on pd.products_id=p.products_id where products_status=1');

while($prods=tep_db_fetch_array($prods_query))
{
    
    $items=split(',',$prods['products_ailments']);
    foreach($items as $item)
    {
        $item=trim($item);
        if(strlen($item)>1){
            if(tep_db_num_rows(tep_db_query('select * from ailments where ailment="'.tep_db_input($item).'"'))>0)
            {
                tep_db_query('update ailments set popularity=popularity+1 where ailment="'.$item.'"');
                
            }else{
                tep_db_query('insert into ailments(ailment, popularity) values("'.tep_db_input($item).'",1)');
            }
        }
    }
    
    
    $items=split(',',$prods['products_uses']);
    foreach($items as $item)
    {
        $item=trim($item);
        if(strlen($item)>1){
            if(tep_db_num_rows(tep_db_query('select * from symptoms where symptom="'.tep_db_input($item).'"'))>0)
            {
                tep_db_query('update symptoms set popularity=popularity+1 where symptom="'.$item.'"');
                
            }else{
                tep_db_query('insert into symptoms(symptom, popularity) values("'.tep_db_input($item).'",1)');
            }
        }
    }     
}

echo 'done';
?>
