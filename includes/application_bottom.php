<?php




/*


// close session (store variables)
  tep_session_close();

  if (STORE_PAGE_PARSE_TIME == 'true') {
    $time_start = explode(' ', PAGE_PARSE_START_TIME);
    $time_end = explode(' ', microtime());
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
    error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);

    if (DISPLAY_PAGE_PARSE_TIME == 'true') {
      echo '<span class="smallText">Parse Time: ' . $parse_time . 's</span>';
    }
  }

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded == true) && ($ini_zlib_output_compression < 1) ) {
    if ( (PHP_VERSION < '4.0.4') && (PHP_VERSION >= '4') ) {
      tep_gzip_output(GZIP_LEVEL);
    }
  }      */
  
$buffer = ob_get_clean();  

if(!$stop_tidy)
{
//Tidy HTML output
$tidy_opt=Array(
                'bare'=>false,
                'clean'=>true,
                'lower-literals'=>true,
                'merge-divs'=>'auto',
                'merge-spans'=>'auto',
                'output-xhtml'=>true,
                'char-encoding'=>'utf8',
                'input-encoding'=>'utf8',
                'output-encoding'=>'utf8',
                'tidy-mark'=>false,
                'doctype'=>'strict' ,
                'drop-proprietary-attributes'=>false,
                'logical-emphasis'=>true,
                'enclose-block-text'=>false,
                'alt-text'=>'',
                'wrap'=>0,
                'quote-ampersand'=>'no'
                );
 
                    
                                                        
  $tidy = new tidy();
  $clean = $tidy->repairString(substr($buffer, strpos($buffer,'<')),$tidy_opt);
}
else
{
    $clean=$buffer;
}
    
$catalog_html=$clean; //Copy the output to give to the link cataloger.

$clean=modURLs($clean);
$clean = str_replace('-or-a-a-','-',$clean);
$clean = str_replace('iso-8859-1','UTF-8', $clean); 
echo utf8_encode($clean);

tep_session_close();


ob_flush();

register_shutdown_function(store_page_links());

function store_page_links()
{
    

    // Do post wrapup
    while(1==1)
    {
        //infinate loop. Check output.
    }


    if($modURL) //Convert the URI back to the original URI.
          {
              $uri=$_SERVER['PHP_SELF'];
              if(count($_GET))
              {
                  $_GET=array_reverse($_GET);
                  $uri.='?';
                  $appendAmp=false;
                  foreach(array_keys($_GET) as $item)
                  {
                      if($appendAmp)
                      {
                          $uri.='&';
                      }
                      
                      $uri.=$item.'='.$_GET[$item];
                      $appendAmp=true;
                  }
              } 
              
              $_SERVER['REQUEST_URI']=$uri;         
          }
          

    $linkable=',/hub.php,/,/index-hidden.php,/remedies/,/catalog/,/catalog.php,/topic.php,/product_info.php,/healthnotes.php,/index.php,/ailments.php,/natural_uses.php,/departments.php,health_library.php,';
    if((strpos($linkable, $_SERVER['SCRIPT_NAME']) || strpos($linkable, $_SERVER['PHP_SELF']) || @strpos($linkable,$processor)) && !strpos($_SERVER['REQUEST_URI'],'buy_now') &&  !strpos($_SERVER['REQUEST_URI'],'qty=') && !strpos($_SERVER['REQUEST_URI'],'?qty'))
      {
          if(!$linkcachetime)
          {
              $linkcachetime=86400; //One day cache
          }
        catalog_links($catalog_html, $linkable, $linkcachetime);
      }              
}        
  

?>
