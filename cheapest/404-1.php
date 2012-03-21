<?php
	
    if(strpos($_SERVER['REQUEST_URI'],'health_library.php')>0)
    {
        //error404();
    }
    
    header("HTTP/1.0 200 OK");
    
    $_SERVER['REQUEST_URI']=substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],'/cheapest/'));
    
      
      
    require($_SERVER['DOCUMENT_ROOT'].'/includes/application_top.php');
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

    $url=str_replace('index.php','',$_SERVER['REQUEST_URI']);
    if(strpos($url,';')>0) {$url=parse_section($url.'<eof>',';','<eof>');}
    if(substr($url,-1,1)!='/') {
        if(strpos($url,'health_library')>0)
        {
            redir301(str_replace('/health_library.php?article='.parse_section($url.'<eof>','article=','<eof>'),'/',$url));
        }
        else
        {
            redir301($url.'/');
        }
    }
    
    $param1=parse_section($url,'cheapest/','-');

    if(is_numeric($param1)){
        require($_SERVER['DOCUMENT_ROOT'].'/cheapest/product_info.php');}
    else{
        require($_SERVER['DOCUMENT_ROOT'].'/cheapest/cheapest-index.php');}
        
    

function error404()

{
    header("HTTP/1.0 404 Not Found");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML><HEAD><TITLE>The page cannot be found</TITLE>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=Windows-1252">
<STYLE type="text/css">
  BODY { font: 8pt/12pt verdana }
  H1 { font: 13pt/15pt verdana }
  H2 { font: 8pt/12pt verdana }
  A:link { color: red }
  A:visited { color: maroon }
</STYLE>
</HEAD><BODY><TABLE width=500 border=0 cellspacing=10><TR><TD>

<h1>The page cannot be found</h1>
The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
<hr>
<p>Please try the following:</p>
<ul>
<li>Make sure that the Web site address displayed in the address bar of your browser is spelled and formatted correctly.</li>
<li>If you reached this page by clicking a link, contact
 the Web site administrator to alert them that the link is incorrectly formatted.
</li>
<li>Click the <a href="javascript:history.back(1)">Back</a> button to try another link.</li>
</ul>
<h2>HTTP Error 404 - File or directory not found.<br>Internet Information Services (IIS)</h2>
<hr>
<p>Technical Information (for support personnel)</p>
<ul>
<li>Go to <a href="http://go.microsoft.com/fwlink/?linkid=8180">Microsoft Product Support Services</a> and perform a title search for the words <b>HTTP</b> and <b>404</b>.</li>
<li>Open <b>IIS Help</b>, which is accessible in IIS Manager (inetmgr),
 and search for topics titled <b>Web Site Setup</b>, <b>Common Administrative Tasks</b>, and <b>About Custom Error Messages</b>.</li>
</ul>

</TD></TR></TABLE></BODY></HTML>

<?php

exit(0);

}    



?>


  