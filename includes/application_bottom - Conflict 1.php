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
                'drop-proprietary-attributes'=>true,
                'logical-emphasis'=>true,
                'enclose-block-text'=>false,
                'alt-text'=>'',
                'wrap'=>0,
                'quote-ampersand'=>'no'
                );
$buffer = ob_get_clean();
$tidy = new tidy();
$clean = $tidy->repairString(str_replace('<head>','<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />',substr($buffer, strpos($buffer,'<'))),$tidy_opt);


echo modURLs($clean);



ob_flush();
$linkable=',/remedies/,/topic.php,/product_info.php,/healthnotes.php,/index.php,/ailments.php,/natural_uses.php,/departments.php,health_library.php,';
if((strpos($linkable, $_SERVER['SCRIPT_NAME']) || strpos($linkable,$processor)) && !strpos($_SERVER['REQUEST_URI'],'buy_now') &&  !strpos($_SERVER['REQUEST_URI'],'qty=') && !strpos($_SERVER['REQUEST_URI'],'?qty'))
  {
    catalog_links($clean, $linkable);
  }                      
  

?>
