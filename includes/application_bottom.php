<head>
	<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
</head>
<?php

$buffer = ob_get_clean();  

if(OPTIMIZE_IMAGES)
{
    //Replace all <img> tags with new optimized URL.
    //$clean=preg_replace('/(src="'. str_replace('/','\/',DIR_WS_IMAGES) .')(.+?)(\.gif"|\.jpg"|\.jpeg"\.png")/im','$1optimized/$2-0x0$3',$clean);
    // Select all images on the page
    preg_match_all('/<img\s.+?>/im',$buffer,$matches);
    
    foreach($matches[0] as $img)
    {
        // Check to see if the images are in the WS_DIR_IMAGES root directory
        if( preg_match('/("'. str_replace('/','\/',DIR_WS_IMAGES) .')(.+?)(\.gif"|\.jpg"|\.jpeg"|\.png")/im',$img,$elements))
        {
            // Get width and height dimensions.
            if(preg_match('/width="([0-9]+?)"|width:([0-9]+?)px/im',$img,$width))
            {
                $width=(int)$width[1]==0 ? $width[2] : $width[1];
                
            }
            else
            {
                $width=0;
            }
            if(preg_match('/height="([0-9]+?)"/im',$img,$height))
            {
                $height=$height[1];
                
            }
            else
            {
                $height=0;
            }
            
            // Replace all old images with new optimized location
            $new_img=str_replace($elements[0],$elements[1].'optimized/'.$elements[2].'-'.$width.'x'.$height.$elements[3],$img);
            $buffer=str_replace($img,$new_img,$buffer);
            
        }
        
       
    }

}

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
$clean = str_replace('stylesheet.css','stylesheet-a.css', $clean);  // Replace cached stylesheet

$clean=utf8_encode($clean); //Strip bad characters

// circumvent tidyhtml
$plusonecode='<g:plusone size="tall"></g:plusone>';

$clean=str_replace('$GOOGLE_PLUS_ONE$', $plusonecode, $clean);




echo $clean;

tep_session_close();


ob_flush();

register_shutdown_function(store_page_links);

function store_page_links()
{
    
    
    global $catalog_html;
    global $linkcachetime;
    global $modURL;
    // Do post wrapup


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
          

    $linkable=',/hub.php,/,/index-hidden.php,/remedies/,/catalog/,/catalog.php,/product_info.php,/healthnotes.php,/index.php,/ailments.php,/natural_uses.php,/departments.php,health_library.php,';
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
