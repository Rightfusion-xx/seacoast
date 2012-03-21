<?php 
/*
  SEO_Assistant for OSC 2.2 MS2 v2.0  08.03.2004
  Originally Created by: Jack_mcs
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/

 for($i=0, $x=1;$i<$supplementaltotal;$i+=$hits_per_page,$x+=$hits_per_page)
 {   
    $filename = "http://www.google.com/search?q=site:$searchurl&num={$hits_per_page}&hl=en&lr=&filter=0&start=$x";
    $conditions = "<span class=a>(.*)</span><nobr>";
    $ttlString = "ut <b>(.*)</b> from";

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
            $totalSupplementalLinks_Google = $out[1]; 
            break;
         }
       }
 
       /******************************** GET THE LINKS ****************************/
       while (!feof($file))  // load the file into a variable line at a time
       {	 
    	   $var = fgets($file, 1024); 
  
         if (eregi($conditions, $var, $out)) // find the html code this SE uses to show the site URL
    	   {  
            if (eregi("Supplemental Result", $var))
            {
            	$out[1] = strtolower(strip_tags($out[1]));
               if (($p1 = strpos($out[1], "supplemental result")) !== FALSE)
               {
                 if (preg_match_all("/(.+?) - .*/",$out[1], $res))
                 {                        
                    $supplementalURL_Google[] = "http://" . $res[1][0];
                    $foundSupplemental_Google++;
                 }
               }    
            }  
         }   
       }
    }		 
    fclose($file);	
 }           
?>