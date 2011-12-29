<?php


$url_patterns=array(

    '/"\S*?hub\.php\?tag=\S+"/i'=>
    
    '$replacement="/".substr($match,strpos($match,"=")+1,strlen($match)-2-strpos($match,"="));',

    '/"\S*?health_library\.php.+?"/i'=>
    
    '
    $replacement=modurls::health_library($match);
    ',

    '/"\S*?search_topics\.php.+?"/i'=>
    
    '
    $replacement=modurls::search_topics($match);
    ',   

    '/"\S*?catalog\.php\?page=\d+"/i'=>
    
    'preg_match(\'/\?page=\d+/i\',$match,$replacement); 
    $replacement=substr($replacement[0],6);
    if(!strlen($replacement))$replacement=1;
    $linktext=tep_db_fetch_array(tep_db_query("select linktext, pagenum from automated_catalog where pagenum=".(int)$replacement));                                                                        

    $replacement="\"/catalog/".seo_url_title($linktext["linktext"], $replacement)."\"";
    ',    
    
    
    '/"\S*?natural_uses\.php\?use=\S+"/i'=> 
    
    'preg_match(\'/\?use=.+?(&|")/i\',$match,$replacement);
    $replacement=substr($replacement[0],5,strlen($replacement[0])-6);
    $replacement="\"/remedies/".seo_url_title($replacement, $page)."\"";',
                                          
    '/"\S*?ailments\.php\?remedy=\S+"/i'=>
    
    'preg_match(\'/\?remedy=.+?(&|")/i\',$match,$replacement);
    $replacement=substr($replacement[0],8,strlen($replacement[0])-9);
    $replacement="\"/ailment/".seo_url_title($replacement, $page)."\"";',
    
    '/"\S*?departments\.php\?benefits=\S+"/i'=>
    
    'preg_match(\'/\?benefits=.+?(&|")/i\',$match,$replacement);
    $replacement=substr($replacement[0],10,strlen($replacement[0])-11);
    $replacement="\"/use/".seo_url_title($replacement, $page)."\"";' ,
    
    '/"\S*?product_info\.php\?products_id=\S+"/i'=>
    
    'preg_match(\'/\?products_id=.+?(&|")/i\',$match,$replacement); 
    $replacement=substr($replacement[0],13,strlen($replacement[0])-14);
    $products_name=tep_db_fetch_array(tep_db_query("select manufacturers_name, products_name from products_description pd join products p on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id where p.products_id=".(int)$replacement));
    $name_parts=parse_nameparts($products_name["products_name"]);
    $replacement="\"/".seo_url_title($name_parts["name"])."/".seo_url_title($products_name["manufacturers_name"])."/".seo_url_title($name_parts["attributes"])."/p".$replacement."\"";
    $replacement=str_replace("//","/",$replacement);',

    '/"\S*?product_info2\.php\?products_id=\S+"/i'=>

        'preg_match(\'/\?products_id=.+?(&|")/i\',$match,$replacement);
        $replacement=substr($replacement[0],13,strlen($replacement[0])-14);
        $products_name=tep_db_fetch_array(tep_db_query("select manufacturers_name, products_name from products_description pd join products p on p.products_id=pd.products_id join manufacturers m on m.manufacturers_id=p.manufacturers_id where p.products_id=".(int)$replacement));
        $name_parts=parse_nameparts($products_name["products_name"]);
        $replacement="\"/".seo_url_title($name_parts["name"])."/".seo_url_title($products_name["manufacturers_name"])."/".seo_url_title($name_parts["attributes"])."/p".$replacement."\"";
        $replacement=str_replace("//","/",$replacement);',


    '/"\S*?index\.php\?manufacturers_id=\S+"/i'=>
    
    'preg_match(\'/\?manufacturers_id=.+?(&|")/i\',$match,$replacement); 
    $replacement=substr($replacement[0],18,strlen($replacement[0])-19);
    $manufacturers_name=tep_db_fetch_array(tep_db_query("select manufacturers_name from manufacturers where manufacturers_id=".(int)$replacement));     
    $replacement="\"/naturalist/".seo_url_title($manufacturers_name["manufacturers_name"]."-".$replacement, $page)."\"";', 
    
    '/"\S*?index\.php\?cPath=\S+"/i'=>
    
    'preg_match(\'/\?cPath=.+?(&|")/i\',$match,$replacement); 
    $replacement=substr($replacement[0],7,strlen($replacement[0])-8);
    $categories_name=tep_db_fetch_array(tep_db_query("select categories_name from categories_description where categories_id=".(int)$replacement));                                                                        
    $replacement="\"/guide/".seo_url_title($categories_name["categories_name"]."-".$replacement, $page)."\"";' 
    
    );
                                                                            

  function modURLs($html)
  {
      
      global $url_patterns;
      
      // Iterate through URL Patterns and run replacement
      if(is_array($url_patterns))
      {
          
          
          // check to see if a cache of the links is available
          $link_cache= new  megacache(60*60*24*30);
          $reset_cache=false;
          
          if($link_arr=$link_cache->doCache('modlinks',false))
          {
              $link_arr=unserialize($link_arr);
              
          }
          
          if(!is_array($link_arr))
          {
              $link_arr=array();
          }
          
          foreach(array_keys($url_patterns) as $pattern)
          {
              if(preg_match_all($pattern,$html,$matches))
              {
                  // URL Replacement matches were found. Now replace the URL
                  foreach($matches[0] as $match)
                  {
                      if(preg_match('/page=\d+/i',$match,$page))
                      {
                          $page=substr($page[0],5);
                      }
                      else
                      {      
                          $page=1;
                      }
                      
                      // check to see if link exists in the link array
                      
                      if(isset($link_arr[$match]))
                      {
                          $html=str_replace($match,$link_arr[$match],$html);                          
                      }
                      else
                      {
                          eval($url_patterns[$pattern]); 
                          $html=str_replace($match,$replacement,$html);
                          $link_arr[$match]=$replacement;
                          $reset_cache=true;
                                                    
                      }
                      
                      
                      
                  }
                  
              }
          }
          
          // Save the array to cache if it changed
          if($reset_cache)
          {
              $link_cache->addCache('modlinks',serialize($link_arr));
          }
                    
      }
      
      
      
      return($html);
      
  }
  
  
function seo_url_title($url_title, $page=1)
{
    $url_title=urldecode($url_title);
    $url_title=preg_replace('/(^-|-$)/','',strtolower(preg_replace('/[^a-z0-9]+/i','-',$url_title)));
    if($page>1)
    {
        $url_title.='-p'.$page;
    }
    return($url_title);
}

function redirect_moded_url()
{
    global $url_patterns, $old_uri;    
    foreach(array_keys($url_patterns) as $pattern)
          {
              if(preg_match_all($pattern,'"'.$_SERVER['REQUEST_URI'].'"',$matches))
              {                                                                                                                  
                  // URL Replacement matches were found. Now replace the URL
                  foreach($matches[0] as $match)
                  {
                      if(preg_match('/page=\d+/i',$match,$page))
                      {
                          $page=substr($page[0],5);
                      }
                      else
                      {                                                                                              
                          $page=1;
                      }
                      
                      // check to see if link exists in the link array
                      
                      eval($url_patterns[$pattern]);
                      redir301(trim($replacement,'"'));                                                    
                      }
                      
                  }
                  
              }
}


class modurls
{
    static function search_topics($url)
    {
        $newurl='/topic/';   //set the base url
        
        if(preg_match('/letter\=([a-z0-9])/i',$url,$matches))
        {
            $newurl.=$matches[1];
            
            // okay to check for page number
            if(preg_match('/page\=([1-9]{1}[0-9]*)/i',$url,$matches))
            {
                $newurl.='-'.$matches[1];
            }            
        }
        
        
        $newurl='"'.$newurl.'"';
        return($newurl);
    }
    
    
    
    static function health_library($url)
    {     
        
        global $url_title, $ContentID;
        
        
        //echo $replacement;exit();
        if(preg_match('/article=\d+/', $url, $replacement))
        {
            $replacement=substr($replacement[0],strpos($replacement[0], '=')+1); 
            //number found, so use the article id as the resource.
            try
            {
                $article=healthnotes::find_by_contentid($replacement);  
                $url="\"/health-guide/".seo_url_title($article->title."-".$article->contentid)."\"";                      
            }
            catch(exception $e)
            {
                //no article found, so die
                 return('/');
            }
            
        }
        elseif(preg_match('/\?.+"/i',$url,$replacement))
        {
            //try to match on resource stub
             
            $replacement=urldecode(substr($replacement[0],1, strlen($replacement[0])-2));
            $asset=substr($replacement,0,strrpos($replacement,'/')).'/~default';  
            
            try
            {
                $article=healthnotes::find_by_resourcepath($asset);                     
                $replacement=preg_replace('/\/.*\//i','',$replacement);
                $url="\"/health-guide/".seo_url_title($article->title."-".$article->contentid."-".$replacement)."\"";  
                
            }
            catch(exception $e)
            {
                return('/');               
                
            }
        }
        else
        {
            
            return('/');
        }
        
        
        $url=preg_replace('/(\d)(-intro|-default|-uses|-quality-of-life|-dos-and-donts)/','$1',$url);
             
        return $url;
    }
    
}
?>
