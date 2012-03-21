<?php
/*
  $Id: site_search.php,v 1.00 2003/10/03 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
//search the indicated direcory for the search term
//does not search within sub-directories - contact author is this is needed
function tep_listdir($dir, $searchTerm)
{
 	 $fileList = array();
	 
   function ls_recursive($dir, $searchTerm)
   {
	     $fileList = array();
       if (is_dir($dir))
       {
           $dirhandle=opendir($dir);
           while(($file = readdir($dirhandle)) !== false)
           {
             if (($file!=".")&&($file!=".."))
             {
                $currentfile=$dir."/".$file;
                if(is_dir($currentfile))
                  continue;
                 
					    $str = $currentfile;
                $pieces = explode('.',$str);
                if ($pieces[1] == 'php' || $pieces[1] == 'html') //NOTE: fails if thirs extionion such file.php.old
  				  	 {
  					   $contents = file_get_contents($currentfile);
								 
  					   if (strstr($contents, $searchTerm)) 
  					   { 
					        $pieces = explode('/',$str);
					        $pieces = explode('.', $pieces[count($pieces) - 1]);
                    
                    $fp = @file(tep_href_link($pieces[0].'.php', '', 'NONSSL'));
                    if ($fp)       //only add to the list if the files is present in the root
                      $fileList[] = $pieces[0];
                  } 
  	    		    }	 
              }
           }
       }
			 return $fileList;
   }

   $fileList = ls_recursive($dir, $searchTerm);
	 return $fileList;
}
?>
