<?php 
/*
  SEO_Assistant for OSC 2.2 MS2 v2.0  08.03.2004
  Originally Created by: Jack_mcs
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/

 $conditionsSID = "osCsid"; 

 for($i=0, $x=1;$i<$checksidstotal;$i+=$hits_per_page,$x+=$hits_per_page)
 {   
    switch ($siteName)
    {
       case "Google": $filename = "http://www.google.com/search?q=site:$searchurl&num={$hits_per_page}&hl=en&lr=&filter=0&start=$x";
                      $conditions = "<span class=a>(.*)</span><nobr>";
                      $ttlString = "ut <b>(.*)</b> from";
       break;
       case "msn":    $filename = "http://search.msn.com/results.aspx?q=site:$searchurl&FORM=MSNH&first=$x&count=".$hits_per_page;
                      $conditions = "<li class=\"first\">(.*)</li>";
                      $ttlString = "Page 1(.*)results";
       break;
       case "Yahoo":  $filename = "http://siteexplorer.search.yahoo.com/search?p=$searchurl&n={$hits_per_page}&b=$page_var";
                      $conditions = "<a class=\"yschttl\"(.*)</a>";
                      $ttlString = "about <strong>(.*)</strong>";
       break;                    
             
       default: echo 'Bad case '.$siteName; exit;
    }

    // Open the search page.
    $file = fopen($filename, "r");
    if (!$file) 
    {
    	echo "<p>Unable to open remote file $filename.\n";
    }
    else
    {    
       /***************************** GET THE TOTAL LINKS *************************/
       $stop = false;
       while (!feof($file))
       { 
         $var = fgets($file, 1024);
        
         if (eregi($ttlString,$var,$out))
         { 
           switch ($siteName)
           {
             case "Google": $totalLinks_Google = $out[1]; $stop = true; break;
             case "Yahoo":  $totalLinks_Yahoo = $out[1];  $stop = true; break;                 
             case "msn":             
              $str = substr($out[0], strlen("Page 1 of "));
              if (($pos = strpos($str, "results")) !== FALSE)
                $str = substr($str, 0, ($pos - 1));
              $totalLinks_Msn = $str;
              $stop = true;
             break;
             
             default: echo 'Bad case '.$siteName; exit;
           }  
           break;
         }
         if ($stop)         
           break;
       }

       /******************************** GET THE LINKS ****************************/
       while (!feof($file))// && $ctr <= $totalLinks)  // load the file into a variable line at a time
       {	 
    	   $var = fgets($file, 1024); 
  
         if (eregi($conditions, $var, $out)) // find the html code this SE uses to show the site URL
    	   {  
            if (eregi($conditionsSID, $var))
            {
               $out[1] = strtolower(strip_tags($out[1]));
  
               switch ($siteName)
               {
                  case "Google":
                  case "msn":
                    if (($p1 = strpos($out[1], "?oscsid")) !== FALSE)
                    {
                      if (($p2 = strpos($out[1], " ", $p1)) !== FALSE)
                      {                        
                        if ($siteName == "Google")
                        {
                          $sidURL_Google[] = "http://" . str_replace(" ", "", (substr($out[1], 0, $p2)));
                          $foundSID_Google++;
                        } 
                        else
                        {
                          $sidURL_Msn[] = "http://" . str_replace(" ", "", (substr($out[1], 0, $p2))); 
                          $foundSID_Msn++; 
                        }
                      }
                    }
                  break;
                  
                  case "Yahoo":
                    if (($p1 = strpos($out[1], "href=\"http")) !== FALSE)
                    {
                      $out[1] = substr($out[1], 7);
                      if (($p2 = strpos($out[1], "\">", $p1)) !== FALSE)
                      { 
                         $sidURL_Yahoo[] = substr($out[1], 0, $p2);
                         $foundSID_Yahoo++;
                      }
                    }
                  break;        
                  
                  default: echo 'Bad case '.$siteName; exit;          
               }    
            }  
         }   
       }
    }		 
    fclose($file);	
 }           
?>