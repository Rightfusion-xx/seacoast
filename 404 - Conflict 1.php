<?php

//echo $_SERVER['REQUEST_URI'] ; exit();

// Need to match the first /test/ as the processor command
if(preg_match('/\/([a-z0-9-]+)\//', $_SERVER['REQUEST_URI'],$processor))
{
 
    $processor=$processor[0];

}
if(preg_match('/\/([a-z0-9-]+$|[a-z0-9-]+-p\d+$)/', $_SERVER['REQUEST_URI'],$url_title))
{
    $url_title=str_replace('/','',$url_title[0]);

}
if(preg_match('/(-p\d+$)/',$_SERVER['REQUEST_URI'],$pagenum))
{
    $pagenum=substr($pagenum[0],2);  
    $url_title=preg_replace('/-p'.$pagenum.'$/','',$url_title);
}
else
{
    $pagenum=1;
}
                 
if($pagenum>1)
{
    $_REQUEST['page']=$pagenum;  
}

switch($processor)
{
    case '/remedies/':
    header("HTTP/1.0 200 OK");  
    $_REQUEST['use']=$url_title; 
    $_GET['use']=$url_title; 
    $_SERVER['PHP_SELF']='natural_uses.php';  
    $modURL=true;
    include('/natural_uses.php');
    break;
    
    
    
    default:
    error404();
    break;
}

 






    
    

function error404()

{
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

    header("HTTP/1.0 404 Not Found");

?>
      <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>
<title>Seacoast Vitamins | Long Gone...</title>
 <meta name="Description" content="A little 404 not found problem..." />
 <meta name="Keywords" content="404, not found" /> 
 <meta name="robots" content="noodp" /> 
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<div id="content">

                   <h1>Hmmmm, this page was never here...</h1>
                   
                   <p>
                   <b>Try a search, instead:</b>
                   
                   <form action="/topic.php" method="get">
                    <input type="text" name="health" size="40"><br/>
                    <input type="submit" value="Search">
                   </form>  
                   </p>

              

               </div>


<?php 

require(DIR_WS_INCLUDES . 'footer.php'); ?>

<br>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

<?php

exit(0);

}    



?>


  