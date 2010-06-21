<?php

$url_patterns=array(
    '/"\S*?natural_uses\.php\?use=\S+"/i'=> 'preg_match(\'/\?use=.+?(&|")/i\',$match,$replacement);
                                          $replacement=substr($replacement[0],5,strlen($replacement[0])-6);
                                          $replacement="\"/remedies/".seo_url_title($replacement, $page)."\"";'
    );


  function modURLs($html)
  {
      global $url_patterns;
      
      // Iterate through URL Patterns and run replacement
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
                  eval($url_patterns[$pattern]);
                  $html=str_replace($match,$replacement,$html);
                  
              }
              
          }
                    
      }
      
      return($html);
      
  }
  
  
function seo_url_title($url_title, $page=1)
{
    $url_title=preg_replace('/(^-|-$)/','',strtolower(preg_replace('/[^a-z0-9]+/i','-',$url_title)));
    if($page>1)
    {
        $url_title.='-p'.$page;
    }
    return($url_title);
}
?>
