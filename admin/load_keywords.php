<?php 

//Keyword loader



set_time_limit(5000);
require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

$keyword_q=tep_db_query('select * from keywords_competition order by rank asc limit 0,10');

while($keyword=tep_db_fetch_array($keyword_q))
{
    //found keywords to load, now search and load
    $gquery=GOOGLE_SEARCH_URL . "num=20&filter=0&q=" . urlencode($keyword['keyword']);
    $results=file_get_contents($gquery);
    $num_results=(int)parse_section($results, '<M>','</M>');

    for($i=1;$i<=$num_results && $i<=20;$i++)
    {
        $sdelim='<R N="' . $i . '">';
		$curres=parse_section($results, $sdelim, '</R>');
		
		$url='/'.parse_section(parse_section(parse_section('<bof>'.$curres,'<U>','</U>').'<eof>','://','<eof>').'<eof>','/','<eof>');
        //echo $curres;
        //$url=parse_section('<bof>'.$curres,'<U>','</U>');
        
        if(tep_db_num_rows(tep_db_query('select * from site_queries where query="'.tep_db_input(strtolower($keyword['keyword'])) . '" and param_id="'.tep_db_input($url).'"'))<1)
        {
            tep_db_query('INSERT INTO site_queries(param_id,query) VALUES("'.tep_db_input($url).'","'.tep_db_input(strtolower($keyword['keyword'])).'")');
        }
        echo '<br/>'.$keyword['keyword'].'='.$url;
        if($num_results>0)
        {
            tep_db_query('delete from keywords_competition where keyword="'.tep_db_input($keyword['keyword']).'"');
        }
        
        
            
    
    }
    
 
}