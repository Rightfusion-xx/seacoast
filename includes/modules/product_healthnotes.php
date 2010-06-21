<?php

hnresults($tags_array['keywords'],$product_info['products_name']);

function hnresults($keywords, $productname)
{
  global $page_links;

// Your Healthnotes-assigned org ID and URL
$org = 'seacoast';
$searchUrl = 'http://www.healthnotes.info/http/search.cfm';


  // Search form should post a field named 'criteria' and optionally 'rowstart' and 'numrows'
  $criteria = rawurlencode($keywords);
  $rowstart = 1;
  $numrows = 10;


if (!empty($criteria))
{
  $numrows=100;
  // Access Healthnotes search service with specified criteria
  $url = sprintf('%s?org=%s&criteria=%s&rowstart=%d&numrows=%d', 
                 $searchUrl, $org, $criteria, $rowstart, $numrows);
    $hn=@file_get_contents($url);
    $pos=-1;
    $final_results='';
    
    while($pos=strpos($hn, '<tr>', $pos+1))
    {

      $cur=substr($hn, $pos);
      $cur=parse_section($cur, 'tr>','</tr');
      $link=parse_section($cur, 'href="', '"');
      $link_title=parse_section($cur, $link.'">','</a>');
      $link=str_replace('healthnotes.php','/health_library.php',$link);
      $link=str_replace('org=seacoast&ContentID','article',$link);
      $link=str_replace('org=seacoast&contentid','article',$link);
      $link=str_replace('org=seacoast&','',$link);
      $cat=parse_section($cur, '<td class="result_category">', '</td>');

      if(strlen($link)>2)
      {
        if(link_exists($link, $page_links))
        {
          $final_results.='<li>'.$cat. ': <a href="'.$link.'">'.$link_title.'</a></li>';
        }
      }
    }


    //if($mflink=link_exists($hn,$page_links))
    //{

}

if(strlen($final_results)>1)
{
echo "<h2>Research Notes for {$productname}</h2>";
echo '<ul>'.$final_results.'</ul>';
}

}


?>


